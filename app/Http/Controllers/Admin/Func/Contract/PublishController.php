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
        $selectInfo = $this->get_params($request, [ 'date', ['date_type', '>=']]);
        $condition = $this->getPagingList($selectInfo, [
            'date' => '=',
        ]);
        $today = date('Y-m-d');
        $rows = $request->rows;
        $order = $request->sort ?? 'date';
        $sort = $request->sortOrder ?? 'desc';
        $sql = "(select if(sub.`type` = 1, if(sub.real_date1, sub.real_date1, sub.real_date), sub.real_date) as `date`,hydm from ".
        "(select p.hydm_on as hydm,p.real_date as real_date,e.real_date as real_date1,p.jys_id,p.type".
            " from rpa_contract_publish p".
            " left JOIN rpa_contract_publish_extra e".
            " on e.hydm = p.hydm_on".
            " where p.category = 1".
            " and (e.category = 1 or e.category is null)) as sub) as tab";
        $list = DB::table(DB::raw("$sql"))
            ->orderBy($order, $sort)->groupBy('date')
            ->where($condition)->where('date', $selectInfo['date_type'], $today)
            ->select(DB::raw("count(*) as count,`date`"))->paginate($rows);
        return $list;
    }

    /**
     * 获取根据日期获取list
     */
    public function getByDate(Request $request)
    {
        $extraList = RpaContractPublishExtra::where('real_date', $request->date)->select(['real_date', 'hydm', 'date'])->get();
        $hydmList = [];
        foreach ($extraList as $v) {
            $hydmList[$v->hydm] = $v->real_date;
        }
        $list1 = RpaContractPublish::where([
            ['real_date', '=', "{$request->date}"],
            ['category', '=', 1]
        ])->get()->toArray();
        $list2 = RpaContractPublish::whereIn('hydm_on', array_keys($hydmList))
            ->where([
                ['type', 1],
                ['category', 1],
            ])
            ->get()->toArray();
        foreach ($list2 as &$v) {
            $v['real_date'] = $request->date;
            $v['type'] = 1;
        }
        $list = array_merge($list1, $list2);
        foreach ($list as &$item) {
            $jys = RpaContractJys::where('id', $item['jys_id'])->first();
            $contract = json_decode($item['content'], true);
            $pz = RpaContractPz::where('id', $contract['pz_id'])->first();
            $item['jys'] = $jys->name;
            $item['pz'] = $pz->name;
            $item['contract'] = $contract;
        }
        if ($list) {
            return $this->ajax_return(200, '成功', $list);
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
        $newDate = $this->getDateAfter($date, 0, 'F1');
        if($newDate) {
            $date = $newDate;
        }
        return $this->getData($date);
    }


    /**
     * 获取数据
     */
    public function getData($date) {
        $newDate = $this->getDateAfter($date, 0, 'F1');
        if($newDate) {
            $date = date('Y-m-d', strtotime($newDate));
        }
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
        $receiverList = RpaContractReceiver::where('status', 1)->get();
        $emailList = [];
        foreach ($receiverList as $v) {
            $emailList[] = $v->email;
        }
        $hyList = [];
        foreach ($list as $k => $v) {
            if(isset($allList[$v['hydm_on']])) {
                $rdate = $allList[$v['hydm_on']]; //指定日期
                if(strtotime($today) >= strtotime($rdate)) {
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
                    if(in_array($v['real_date'], $allList[$v['hydm_on']])) {
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

        $data = [
            'title' => '合约费用调整',
            'content' => json_encode($list, true),
            'tid' => 2,
        ];


        if(!$list) {
            // 合约调整
            $date1 = RpaContractPublish::where([
                ['real_date', '>', $today],
                ['type', '=', 2]
            ])->orderBy('real_date', 'asc')->first();
            //合约上市
            $date2 = RpaContractPublish::where([
                ['real_date', '>', $today],
                ['type', '=', 1]
            ])->whereNotIn('hydm_on', array_keys($hydmList))->orderBy('real_date', 'asc')->first();
            //指定日期
            $date3 = RpaContractPublishExtra::where('real_date', '>', $today)->orderBy('real_date', 'asc')->first();

            $dList = [];
            if($date1) $dList[] = strtotime($date1->real_date);
            if($date2) $dList[] = strtotime($date2->real_date);
            if($date3) $dList[] = strtotime($date3->real_date);

            if(empty($dList)){
                $content = '';
            } else {
                sort($dList);
                $nextDate = date('Y-m-d', $dList[0]);
                $diffDay = round((strtotime($nextDate)-strtotime($today))/3600/24);
                $content = "<br /> 距下一个合约调整日期($nextDate), 还有 $diffDay 天";
            }
            $data['content'] = "<h2 style='text-align: center'>今日无需要调整的合约提醒{$content}</h2>";
            $data['title'] .= "--今日无调整";
        }
        $sysmail = SysMail::create($data);
        $to = $emailList;
        $result = (new MdEmail($sysmail, 'mail.test'));
        Mail::to($to)->send($result);
        $re = [
            'status' => 200,
            'msg' => '发送成功'
        ];

        return $re;
    }
}