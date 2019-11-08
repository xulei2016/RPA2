<?php


namespace App\Http\Controllers\Admin\Func\Contract;


use App\Mail\MdEmail;
use App\models\admin\base\SysMail;
use App\Models\Admin\Func\Contract\RpaContractJys;
use App\Models\Admin\Func\Contract\RpaContractPublish;
use App\Models\Admin\Func\Contract\RpaContractPublishExtra;
use App\Models\Admin\Func\Contract\RpaContractPz;
use App\Models\Admin\Func\Contract\RpaContractReceiver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PublishController extends BaseController
{
    protected $view_prefix = "admin.func.contract.publish.";

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $jys = RpaContractJys::get();
        $this->log(__CLASS__, __FUNCTION__, $request, "合约-推送信息 列表页");
        return view($this->view_prefix . 'index', ['jys' => $jys]);
    }

    /**
     * 列表数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['jys_id', 'type', 'date']);
        $condition = $this->getPagingList($selectInfo, [
            'jys_id' => '=',
            'type' => '=',
            'date' => '=',
        ]);
        $condition[] = ['category', '=', 1];
        $rows = $request->rows;
        $order = $request->sort ?? 'date';
        $sort = $request->sortOrder ?? 'desc';
        $subQuery = RpaContractPublish::where($condition)->groupBy('date')->select(DB::raw("count(id) as count,`date`"));
        $list = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))->orderBy($order, $sort)->mergeBindings($subQuery->getQuery())->paginate($rows);
        return $list;
    }

    /**
     * 获取根据日期获取list
     */
    public function getByDate(Request $request)
    {
        $list = RpaContractPublish::where([
            ['date', '=', "{$request->date}"],
            ['category', '=', 1]
        ])->select(['jys_id', 'contract_id', 'category', 'type', 'date', 'hydm_on', 'hydm_off', 'content', 'real_date'])->get();
        foreach ($list as &$item) {
            $jys = RpaContractJys::where('id', $item->jys_id)->first();
            $contract = json_decode($item->content, true);
            $pz = RpaContractPz::where('id', $contract['pz_id'])->first();
            $item->jys = $jys->name;
            $item->pz = $pz->name;
            $item->contract = $contract;
            if($item->type == 1) {
                $extra = RpaContractPublishExtra::where([
                    ['category', '=', 1],
                    ['hydm', '=', $item->hydm_on]
                ])->first();
                if($extra) {
                    $item->real_date = $extra->real_date;
                }

            }
        }
        if ($list) {
            return $this->ajax_return(200, '成功', $list->toArray());
        } else {
            return $this->ajax_return(500, '没有更多的数据');
        }
    }

    /**
     * 推送
     */
    public function publish()
    {
        (new DetailController())->updateAll(); //先更新全部
        $date = date('Y-m-d');
        return $this->getData($date);
    }

    /**
     * 获取数据
     */
    public function getData($date) {
        $today = $date;
        $extraList = RpaContractPublishExtra::where('date', $date)->select(['real_date', 'hydm', 'date'])->get();
        $hydmList = [];
        foreach ($extraList as $v) {
            $hydmList[$v->hydm] = $v->real_date;
        }
        $list = RpaContractPublish::where('date', $date)
            ->orWhere(function ($query) use ($hydmList) {
                $query->where('type', 1)->whereIn('hydm_on', array_keys($hydmList));
            })
            ->get()
            ->toArray();
        $allExtraList = RpaContractPublishExtra::select(['real_date', 'hydm'])->get();
        $allList = [];
        foreach ($allExtraList as $v) {
            $allList[$v->hydm] = $v->real_date;
        }
        if (!$list) {
            $re = [
                'status' => 200,
                'msg' => '没有需要发送的数据'
            ];
        } else {
            $receiverList = RpaContractReceiver::all();
            $emailList = [];
            foreach ($receiverList as $v) {
                $emailList[] = $v->email;
            }
            $hyList = []; 
            foreach ($list as $k => $v) {
                if(isset($allList[$v['hydm_on']])) {
                    $rdate = $allList[$v['hydm_on']]; //指定日期
                    if(strtotime($today) >= strtotime($hyAllList[$v['hydm_on']][0])) {
                        $list[$k]['real_date'] = $rdate;
                    }
                }
            }
            foreach($list as $k => $v) {
                $hyCode = $v['hydm_on'].'-'.$v['type'];
                if(isset($allList[$v['hydm_on']])) { // 判断该合约是否被指定
                    if($v['type'] == 2) { //运行调整
                        $hyList[] = $hyCode;
                    } else { //上下市
                        $rdate = $allList[$v['hydm_on']]; //指定日期
                        if(strtotime($today) > strtotime($v['real_date'])) { // 指定日期小于今日  直接删除
                            unset($list[$k]);
                            continue;
                        }
                        if(in_array($v['real_date'], $hyAllList[$v['hydm_on']])) {
                            if(in_array($hyCode, $hyList)) {
                                unset($list[$k]);
                                continue;
                            } else {
                                $list[$k]['real_date'] = $rdate;
                                $hyList[] = $hyCode;   
                            }
                        } else {
                            unset($list[$k]);
                            continue;
                        }
                    }
                } else {
                    $hyList[] = $hyCode;
                }
                $item = json_decode($v['content'], true);
                $list[$k]['jys'] = RpaContractJys::where('id', $v['jys_id'])->first()->name;
                $list[$k]['pz'] = RpaContractPz::where('id', $item['pz_id'])->first()->name;
                if($v['type'] == 1) {
                    $type = "新合约上市";
                    $sxf = $item['xhy_jysxf'];
                    $rnfy = $item['xhy_rnfy'];
                    $hydm_on = $v['hydm_on'];
                    $hydm_off = $v['hydm_off'];
                    $hydm_tz = '-';
                    $sxf_before = '-';
                    $rnfy_before = '-';
                } else {
                    $type = "交割月前第{$item['tz_month']}月的第{$item['tz_day']}个交易日";
                    $sxf = $item['tz_jysxf'];
                    $rnfy = $item['tz_rnfy'];
                    $hydm_on = '-';
                    $hydm_off = '-';
                    $hydm_tz = $v['hydm_on'];
                    $sxf_before = $item['has_online']?$item['xhy_jysxf']:$item['pzfy_jysxf'];
                    $rnfy_before = $item['has_online']?$item['xhy_rnfy']:$item['pzfy_rnfy'];
                }
                
                $list[$k]['typeName'] = $type;
                $list[$k]['sxf'] = $sxf;
                $list[$k]['rnfy'] = $rnfy;
                $list[$k]['hydm_off'] = $hydm_off;
                $list[$k]['hydm_on'] = $hydm_on;
                $list[$k]['hydm_tz'] = $hydm_tz;
                $list[$k]['sxf_before'] = $sxf_before;
                $list[$k]['rnfy_before'] = $rnfy_before;
                unset($list[$k]['content']);
            }
            if(!$list) {
                $re = [
                    'status' => 200,
                    'msg' => '没有需要发送的数据'
                ];
            } else {
                $data = [
                    'title' => '合约费用调整',
                    'content' => json_encode($list, true),
                    'tid' => 2
                ];
                $sysmail = SysMail::create($data);
                $to = $emailList;
                $result = (new MdEmail($sysmail, 'mail.test'));
                Mail::to($to)->send($result);
                $re = [
                    'status' => 200,
                    'msg' => '发送成功'
                ];
            }
        }
        return $re;
    }
}