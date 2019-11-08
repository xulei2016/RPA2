<?php

namespace App\Http\Controllers\Admin\Func\Contract;

use App\Models\Admin\Func\Contract\RpaContractDetail;
use App\Models\Admin\Func\Contract\RpaContractPublish;
use App\Models\Admin\Func\Contract\RpaContractPublishExtra;
use App\Models\Admin\Func\Contract\RpaContractReceiver;
use App\Models\Admin\Func\Contract\RpaContractDict;
use App\Models\Admin\Func\Contract\RpaContractJys;
use App\Models\Admin\Func\Contract\RpaContractPz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MdEmail;
use App\models\admin\base\SysMail;

/**
 * 合约 详细表
 * Class DetailController
 * @package App\Http\Controllers\Admin\Func\Contract
 */
class DetailController extends BaseController
{
    /**
     * @var string 页面前缀
     */
    protected $view_prefix = "admin.func.contract.detail.";

    protected $dayNumber; // 需提前几日提醒

    protected $pzList; // 品种列表

    protected $jysList; // 交易所列表

    protected $dayList; // 交易日列表

    public function __construct(){
        $this->dayNumber = RpaContractDict::where('name', 'DAY_NUMBER')->first()->value;
        $this->pzList = RpaContractPz::getList();
        $this->jysList = RpaContractJys::getList();
    }

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "合约-品种 列表页");
        $jys = RpaContractJys::get();
        return view($this->view_prefix.'index', ['jys' => $jys]);
    }

    /**
     * 数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request) {
        $selectInfo = $this->get_params($request, ['jys_id']);
        $condition = $this->getPagingList($selectInfo, ['jys_id' => '=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $list = RpaContractDetail::where($condition)->orderBy($order, $sort)->paginate($rows);
        foreach ($list as &$v) {
            $jys = RpaContractJys::where('id', $v->jys_id)->first();
            $pz = RpaContractPz::where('id', $v->pz_id)->first();
            $v->jys = $jys?$jys->name:'暂无';
            $v->pz = $pz?$pz->name:'暂无';
            $v->hy_month = $this->hyMonth($v->hy_month);
        }
        return $list;
    }

    /**
     * 新增页
     */
    public function create()
    {
        $jys = RpaContractJys::get();
        return view($this->view_prefix.'add', ['jys' => $jys]);
    }

    /**
     * 新增保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, [
            'jys_id','pz_id','pzfy_jysxf','pzfy_rnfy','hy_month',
            ['has_online', 0],
            'xhy_month','xhy_day','xhy_day_type','xhy_day_after','xhy_jysxf','xhy_rnfy',
            ['has_change', 0],
            'tz_month','tz_day','tz_jysxf','tz_rnfy'
        ]);
        $data['hy_month'] = implode(',', $data['hy_month']);
        $data['created_by'] = auth()->guard()->user()->id;
        $data['updated_by'] = auth()->guard()->user()->id;
        if(!$data['has_change']) {
            unset($data['tz_month'], $data['tz_day'], $data['tz_jysxf'], $data['tz_rnfy']);
        }
        if(!$data['has_online']) {
            unset($data['xhy_month'], $data['xhy_day'], $data['xhy_day_type'],
             $data['xhy_day_after'], $data['xhy_jysxf'], $data['xhy_rnfy']);
        }
        $result = RpaContractDetail::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 合约-详细");
        $this->dayList = $this->getAllDays();

        $this->publish($data, $result->id);
        if($result) {
            return $this->ajax_return(200, '操作成功！');
        } else {
            return $this->ajax_return(500, '保存失败！');
        }
    }

    /**
     * 编辑页
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $jys = RpaContractJys::get();
        $info = RpaContractDetail::where('id', $id)->first();
        $pz = RpaContractPz::where('jys_id', $info->jys_id)->get();
        $info->hy_month = explode(',', $info->hy_month);
        return view($this->view_prefix.'edit', ['info' => $info, 'jys' => $jys, 'pz' => $pz]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, [
            'id',
            'jys_id','pz_id','pzfy_jysxf','pzfy_rnfy','hy_month',
            ['has_online', 0],
            'xhy_month','xhy_day','xhy_day_type','xhy_day_after','xhy_jysxf','xhy_rnfy',
            ['has_change', 0],
            'tz_month','tz_day','tz_jysxf','tz_rnfy'
        ]);
        $data['updated_by'] = auth()->guard()->user()->id;
        $data['hy_month'] = implode(',', $data['hy_month']);
        if(!$data['has_change']) {
            $data['tz_month'] = null;
            $data['tz_day'] = null;
            $data['tz_jysxf'] = null;
            $data['tz_rnfy'] = null;
        }
        if(!$data['has_online']) {
            $data['xhy_month'] = null;
            $data['xhy_day'] = null;
            $data['xhy_day_type'] = null;
            $data['xhy_day_after'] = null;
            $data['xhy_jysxf'] = null;
            $data['xhy_rnfy'] = null;
        }
        $result = RpaContractDetail::where('id', $data['id'])->update($data);
        $this->dayList = $this->getAllDays();
        $this->publish($data, $data['id']);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 合约-详细");
        if($result) {
            return $this->ajax_return(200, '操作成功！');
        } else {
            return $this->ajax_return(500, '保存失败！');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @param $ids
     */
    public function destroy(Request $request, $ids){
        $today = date('Y-m-d');
        $ids = explode(',', $ids);
        foreach($ids as $id) {
            $contract = RpaContractDetail::where('id', $id)->first();
            RpaContractPublish::where('contract_id', $contract->id)->where('date', '>', $today)->delete();
            $contract->delete();
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 合约-合约模板");
        return $this->ajax_return('200', '操作成功');
    }

    /**
     * 查询界面
     * @param Request $request
     * @param $id
     */
    public function show(Request $request, $id) {
        // $date = date('Y-m-d');
        $date = $request->date;
        $re = (new PublishController())->getData($date);
        return $this->ajax_return($re['status'], $re['msg']);
        
    }

    //获取实际日期和需要提醒的日期
    public function getDayList($date, $number, $jysCode){
        $list = $this->lists[$jysCode];
        $count = count($list);
        $start = $list[0];
        $end = $list[$count-1];
        if($date > $end) {
            return false;
        }
        $rlist = array_flip($list);
        while(true) {
            if(isset($rlist[$date])) {
                $index = $rlist[$date];
                break;
            } else {
                $date ++;
            }
        }
        $realDate = $list[$index];
        $extra = [];
        for($i = 1; $i <= 2; $i++) {
            $extra[] = $list[$index-$i];
        }
        return [
            'realDate' => $realDate,
            'extra' => $extra,
        ];
    }

    //获取某一日期后第几日
    public function getDayAft($date, $afterDay, $jysCode){
        $list = $this->lists[$jysCode];
        $count = count($list);
        $start = $list[0];
        $end = $list[$count-1];
        if($date > $end) {
            return false;
        }
        $rlist = array_flip($list);
        while(true) {
            if(isset($rlist[$date])) {
                $index = $rlist[$date];
                break;
            } else {
                $date ++;
            }
        }
        return $list[$index+$afterDay];
    }

    /**
     * 更新全部
     */
    public function updateAll(){
        $this->dayList = $this->getAllDays();
        $list = RpaContractDetail::get()->toArray();
        foreach($list as $k => $v) {
            $this->publish($v, $v['id']);
        }
        return $this->ajax_return(200, '成功');
    }

    /**
     * 信息推送存储
     */
    public function publish($data, $contractId) {
        $number = $this->dayNumber; // 提前多少天提醒
        $monthList = explode(',', $data['hy_month']); // 合约月份列表
        $thisYear = date('Y');
        $today = date('Y-m-d');
        $pz = $this->pzList[$data['pz_id']];
       
        $jysId =  $data['jys_id'];
        $jys = $this->jysList[$jysId];
        $jysCode = $jys['code'];
        if(isset($data['xhy_day_after']) && $data['xhy_day_after']) {  // 后第(?)交易日
            $afterDay = $data['xhy_day_after'];
        } else {
            $afterDay = 0;
        }
        $list = [];
        $content = json_encode($data, JSON_UNESCAPED_UNICODE);
        RpaContractPublish::where([
            ['contract_id', '=', $contractId],
            ['date', '>=', $today],
        ])->delete();
        for($i = 0; $i < 3; $i++) {
            $realYear = $thisYear + $i; // 推算年份
            foreach ($monthList as $m) {
                $realMonth = $realYear .'-'.$m;
                $hydmOn = $pz['code'].substr(date('Ym', strtotime($realMonth)), -4);
                if($data['has_online']) { // 是否有上线合约
                    $month = $data['xhy_month']; //交割月前(?)个月
                    $dayType = $data['xhy_day_type']; //日期类型  1自然日 2交易日
                    $day = $data['xhy_day'];
                    $onlineTime = strtotime($realMonth . " -$month month");
                    if($dayType == 1) { //自然日
                        $date = date('Y-m', $onlineTime).'-'.$day;
                        $remindDate = $this->getDateAfter($date, $afterDay, $jysCode);
                        $dateList = $this->returnDays($remindDate, $number, $jysCode);
                    } else { // 交易日
                        $remindDate = $this->getFixedDate($jysCode, $day+$afterDay, $m);
                        $dateList = $this->returnDays($remindDate, $number, $jysCode);
                    }
                    if($dateList) {
                        $realTime = strtotime($dateList['realDate']);
                        $realDate = date('Y-m-d', $realTime);
                        $hydmOff = $pz['code'].substr(date('Ym', $onlineTime), -4);
                        if($realTime > time()) { // 之前的不做处理
                            RpaContractPublish::create([
                                'contract_id' => $contractId,
                                'jys_id' => $jysId,
                                'type' => 1,
                                'category' => 1,
                                'date' => $realDate,
                                'hydm_off' => $hydmOff,
                                'hydm_on' => $hydmOn,
                                'content' => $content,
                                'real_date' => $realDate,
                            ]);
                            foreach($dateList['extra'] as $v) {
                                RpaContractPublish::create([
                                    'contract_id' => $contractId,
                                    'jys_id' => $jysId,
                                    'type' => 1,
                                    'category' => 2,
                                    'date' => date('Y-m-d', strtotime($v)),
                                    'hydm_off' => $hydmOff,
                                    'hydm_on' => $hydmOn,
                                    'content' => $content,
                                    'real_date' => $realDate,
                                ]);
                            }
                        }
                    }
                }
                

                if($data['has_change']) { // 表示有运行中调整
                    $tzMonth = $data['tz_month'];
                    $tzDay = $data['tz_day'];
                    $tzTime = strtotime($realMonth. " -$tzMonth month");
                    $tzDate = $this->getFixedDate($jysCode, $tzDay, date('m', $tzTime), date('Y', $tzTime));
                    $content = json_encode($data, JSON_UNESCAPED_UNICODE);
                    $hydmOff = '-';
                    if($tzDate) {
                        $tzDateList = $this->returnDays($tzDate, $number, $jysCode);
                        $tzRealTime = strtotime($tzDateList['realDate']);
                        $tzRealDate = date('Y-m-d', $tzRealTime);
                        if($tzRealTime > time()) {
                            RpaContractPublish::create([
                                'contract_id' => $contractId,
                                'jys_id' => $jysId,
                                'type' => 2,
                                'category' => 1,
                                'date' => $tzRealDate,
                                'hydm_off' => $hydmOff,
                                'hydm_on' => $hydmOn,
                                'content' => $content,
                                'real_date' => $tzRealDate,
                            ]);
                            foreach($tzDateList['extra'] as $v) {
                                RpaContractPublish::create([
                                    'contract_id' => $contractId,
                                    'jys_id' => $jysId,
                                    'type' => 2,
                                    'category' => 2,
                                    'date' => date('Y-m-d', strtotime($v)),
                                    'hydm_off' => $hydmOff,
                                    'hydm_on' => $hydmOn,
                                    'content' => $content,
                                    'real_date' => $tzRealDate,
                                ]);
                            }
                        }
                    }
                }
                
            }
        }
    }
    
    /**
     * 测试邮件发送
     */
    public function testEmail(Request $request){
        $date = $request->date;
        $re = (new PublishController())->getData($date);
        return $this->ajax_return($re['status'], $re['msg']);
    }

}