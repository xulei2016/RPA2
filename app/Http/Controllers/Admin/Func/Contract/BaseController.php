<?php


namespace App\Http\Controllers\Admin\Func\Contract;

use App\Http\Controllers\base\BaseAdminController;
use Illuminate\Http\Request;

class BaseController extends BaseAdminController
{
    /**
     * @var string 页面前缀
     */
    protected $view_prefix;

    public $dayList; // 所有交易日

    public function __construct(){
        parent::__construct();
        if(!$this->dayList) $this->dayList = $this->getAllDays();
    }

    /**
     * 首页
     * @param Request $request
     */
    public function index(Request $request){}

    /**
     * 数据
     * @param Request $request
     */
    public function pagination(Request $request) {}

    /**
     * 新增页
     */
    public function create(){
        return view($this->view_prefix.'add');
    }

    /**
     * 新增保存
     * @param Request $request
     */
    public function store(Request $request){}

    /**
     * 编辑页
     * @param Request $request
     * @param $id
     */
    public function edit(Request $request, $id){}

    /**
     * 更新数据
     * @param Request $request
     */
    public function update(Request $request){}

    /**
     * 删除
     * @param Request $request
     * @param $ids
     */
    public function destroy(Request $request, $ids){}

    /**
     * 查询界面
     * @param Request $request
     * @param $id
     */
    public function show(Request $request, $id) {}


     //**************工具类**************

    /**
     * 月份展示
     * @param $str
     * @return string
     */
    public function hyMonth($str){
        $months = explode(',', $str);
        if(count($months) == 12) {
            return "全年";
        }
        $all = range(1, 12);
        $newList = []; // 非xxx月数组
        foreach ($all as $v) {
            if(!in_array($v, $months)) {
                $newList[] = $v;
            }
        }
        if(count($newList) <= 4) {
            return "非".implode('、',$newList)."月合约";
        } else {
            return implode('、',$months)."月合约";
        }
    }

    /**
     * 自然日遇节假日顺延 返回实际日期
     * @param $date 20190101
     * @param $jys string 交易所代码  F1 - F5
     * @return
     */
    public function calendarDay($date, $jys){
        $date = date('Ymd', strtotime($date));
        $sql = "select * from (select EXCHANGE_TYPE,INIT_DATE from dcuser.tfu_tjyr_hs where EXCHANGE_TYPE = '{$jys}' and INIT_DATE >= {$date}  order by INIT_DATE asc) where rownum < 2"; //查询交易日
        $jyrData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JYBM',
                'by' => $sql 
            ]
        ];
        $jyr = $this->getCrmData($jyrData);
        return $jyr[0]['INIT_DATE'];
    }

    /**
     * 获取指定年月的第$day交易日真实日期
     * @param $jys string 交易所
     * @param $day
     * @param bool $month
     * @param bool $year
     */
    public function getFixedDate($jys, $day, $month = false, $year = false) {
        if(!$year) $year = date('Y');
        if(!$month) $month = date('m');
        $date = date('Ym', strtotime($year.'-'.$month));
        $sql = "select EXCHANGE_TYPE,INIT_DATE from dcuser.tfu_tjyr_hs where EXCHANGE_TYPE = '{$jys}' and INIT_DATE like '%{$date}%'  order by INIT_DATE asc"; //查询交易日
        $jyrData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JYBM',
                'by' => $sql 
            ]
        ];
        $jyr = $this->getCrmData($jyrData);
        if(!$jyr) return false;
        return $jyr[$day-1]['INIT_DATE'];
    }

    /**
     * 获取某一日期后第几交易日
     * @param $date string 日期  交易日
     * @param $number string 后几天
     * @param $jys string 交易所
     */
    public function getDateAfter($date, $number, $jys){
        $date = date('Ymd', strtotime($date));
        $day = $this->dayList;
        $jys = trim($jys);
        $list = $day[$jys];
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
        return $list[$index+$number];
    }

    /**
     * 根据指定日期和提醒的天数返回日期数组
     * @param $date string 日期
     * @param $jys string 交易所
     * @return mixed
     */
    public function returnDays($date, $number, $jys){
        if(!$date) return false;
        $date = date('Ymd', strtotime($date));
        $list = $this->dayList[$jys];
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

    /**
     * 根据指定日期和提醒的天数返回日期数组
     * @param $date string 日期
     * @param $jys string 交易所
     * @return mixed
     */
    public function returnDaysOld($date, $number, $jys){
        $date = date('Ymd', strtotime($date));
        $sql = "select * from (select EXCHANGE_TYPE,INIT_DATE from dcuser.tfu_tjyr_hs where EXCHANGE_TYPE = '{$jys}' and INIT_DATE < {$date}  order by INIT_DATE desc) where rownum <= $number"; //查询交易日
        $jyrData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JYBM',
                'by' => $sql 
            ]
        ];
        $jyr = $this->getCrmData($jyrData);
        if(!$jyr) return false;
        $list = [];
        foreach($jyr as $v) {
            $list[] = $v['INIT_DATE'];
        }
        return [
            'realDate' => $date,
            'extra' => $list,
        ];
    } 
    
    /**
     * 获取全部交易日
     */
    public function getAllDays(){
        $sql = "select EXCHANGE_TYPE,INIT_DATE from dcuser.tfu_tjyr_hs order by INIT_DATE asc"; //查询交易日
        $jyrData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JYBM',
                'by' => $sql 
            ]
        ];
        $jyr = $this->getCrmData($jyrData);
        
        $newList = [];
        foreach($jyr as $v) {
            $jys = trim($v['EXCHANGE_TYPE']);
            $newList[$jys][] = $v['INIT_DATE'];
        }
        return $newList;
    }
}