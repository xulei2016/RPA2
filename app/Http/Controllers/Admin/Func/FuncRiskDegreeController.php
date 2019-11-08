<?php


namespace App\Http\Controllers\Admin\Func;


use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Func\Risk\FuncRiskDegree;
use App\Models\Admin\Func\Risk\FuncRiskQuery;
use Illuminate\Http\Request;

class FuncRiskDegreeController extends BaseAdminController
{

    private $view_prefix = 'admin.func.risk.';

    public $objPHPExcel;

    public $cellKey = [
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
    ];

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        dd('功能已下线');
        $result = FuncRiskDegree::orderBy('rq', 'desc')->first();
        if($result) {
            $date = date('Y-m-d', strtotime($result->rq));
        } else {
            $date = '';
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "风险指标 列表页");
        return view($this->view_prefix.'index', ['date' => $date]);
    }

    /**
     * 数据页
     * @param Request $request
     */
    public function pagination(Request $request){
        $result = $this->getCondition($request);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $list = $result->orderBy($order, $sort)->paginate($rows);
        return $list;
    }

    /**
     * 处理查询条件
     * @param $request
     */
    public function getCondition($request){
        $selectInfo = $this->get_params($request, ['rq', 'khxm', 'jgbz', 'zjzh', 'bzj_rate','jys_rate', 'pz1_rate', 'exp1', 'two']);
        if(isset($selectInfo['rq'])) $selectInfo['rq'] = date('Ymd', strtotime($selectInfo['rq']));
        $selectInfo['bzj_rate'] = round($selectInfo['bzj_rate']/100, 2);
        $selectInfo['jys_rate'] = round($selectInfo['jys_rate']/100, 2);
        $selectInfo['pz1_rate'] = round($selectInfo['pz1_rate']/100, 2);
        $selectInfo['exp1'] = round($selectInfo['exp1']/100, 2);
        $condition = $this->getPagingList($selectInfo, [
            'rq'=>'=',
            'khxm'=>'like',
            'zjzh'=>'=',
            'jgbz'=>'=',
            'bzj_rate'=>'>=',
            'jys_rate'=>'>=',
            'pz1_rate'=>'>=',
            'exp1'=>'>=',
        ]);
        if($selectInfo['two']) { //连续两日
            $newCondition = $condition;
            array_shift($newCondition);
            $newCondition[] = ['rq', '=', date('Ymd', strtotime('-1 day', strtotime($selectInfo['rq'])))];  
            $khh = FuncRiskDegree::where($newCondition)->select('khh');
            return FuncRiskDegree::where($condition)->whereIn('khh', $khh);
        } else {
            return FuncRiskDegree::where($condition);
        }
    }

    /**
     * 查询交易日
     * @param Request $request
     * @return array
     */
    public function getQueryDay(Request $request){
        $result = FuncRiskQuery::where('status', 2)->select('rq')->get();
        $list = [];
        foreach ($result as $k =>  $v) {
            $rq = date('Y-n-j', strtotime($v->rq));
            $list[$rq] = "已查询";
        }
        return $this->ajax_return(200, '查询成功', $list);
    }

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request){
        ini_set("memory_limit", "1024M");
        $result = $this->getCondition($request);
        if($request->has('id')){
            $data = $result->whereIn('id', explode(',',$request->get('id')))->get()->toArray();
        }else{
            $data = $result->get()->toArray();
        }
        $title = "风险指标";
        $fields = [
            '客户号','资金账号','交易所保证金','公司保证金','当日权益', '公司风险度','交易所风险度',
            '品种A','品种A集中度','品种A敞口',
            '营业部','客户经理','姓名','手机号','客户类型','结算日期'
        ];
        $jgbzList = [
            0 => '个人',
            1 => '机构',
            2 => '自营',
            3 => '特殊客户'
        ];
        $this->objPHPExcel = new \PHPExcel();
        $this->objPHPExcel->getProperties()->setCreator("excel")
            ->setLastModifiedBy("excel")
            ->setTitle($title)
            ->setSubject("excel")
            ->setDescription("excel")
            ->setKeywords("excel")
            ->setCategory("excel");
        try {
            $this->objPHPExcel->getActiveSheet()->setTitle($title);
            $this->objPHPExcel->getActiveSheet()->getStyle('A')->getNumberFormat()
                ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $this->objPHPExcel->getActiveSheet()->getStyle('B')->getNumberFormat()
                ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $this->objPHPExcel->getActiveSheet()->getStyle('N')->getNumberFormat()
                ->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $fieldIndex = 0;
            foreach ($fields as $v) {
                $this->setCellHeader($this->cellKey[$fieldIndex]."1", $v);
                $fieldIndex++;
            }
            $index = 2;
            foreach ($this->getList($data) as  $v) {
                $lineData = [
                    $v['khh'], $v['zjzh'], $v['bzj_jys'], $v['bzj'], $v['brjc'], transNumber($v['bzj_rate']), transNumber($v['jys_rate']),
                    $v['pz1'], transNumber($v['pz1_rate']), transNumber($v['exp1']),
                    $v['yyb'], $v['khjl'], $v['khxm'], $v['phone'], $jgbzList[$v['jgbz']], $v['rq']
                ];
                foreach ($lineData as $k => $cell) {
                    $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($this->cellKey[$k] . ($index), $cell);
                }
                unset($lineData);
                $index++;
            }
            $styleThinBlackBorderOutline = array(
                'borders' => array(
                    'allborders' => array( //设置全部边框
                        'style' => \PHPExcel_Style_Border::BORDER_THIN
                    )
                ),
            );
            $this->objPHPExcel->getActiveSheet()->getStyle( 'A1:P'.($index-1))->applyFromArray($styleThinBlackBorderOutline);
            $this->exportExcel($title);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param $list
     * @return \Generator
     */
    public function getList($list){
        foreach($list as $v) {
            yield $v;
        }
    }

    /*
    * 设置单元格
    */
    public function setCell($cell, $value){
        $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue($cell, $value);
        $this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
    }

    /*
     * 设置单元格并且带加粗
     */
    public function setCellHeader($cell, $value,  $fontSize = 0){
        $this->setCell($cell, $value);
        $this->objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setBold(true);
        if($fontSize) $this->objPHPExcel->getActiveSheet()->getStyle($cell)->getFont()->setSize($fontSize);
    }

    /**
     * 导出
     * @param $title
     */
    public function exportExcel($title){
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$title.'.xls"');
        header("Content-Disposition:attachment;filename={$title}.xls");//attachment新窗口打印inline本窗口打印
        try {
            $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * 获取数据
     */
    public function getData(Request $request){
        ini_set("max_execution_time", 1800);
        
        $date = date('Ymd', strtotime($request->date));
        FuncRiskQuery::where('rq', $date)->update([
            'status' => 4
        ]);
        $query = FuncRiskQuery::create([
            'rq' => $date,
            'created_by' => auth()->guard('admin')->user()->realName,
            'status' => 1
        ]);
        FuncRiskDegree::where('rq', $date)->delete();
        $ohost = env('DB_HOST');
        $ouser = env('DB_USERNAME');
        $opwd = env('DB_PASSWORD');
        $odb = env('DB_DATABASE');
        $port = env('DB_PORT');
        $oconn = mysqli_connect($ohost,$ouser,$opwd,$odb,$port);
        $yybList = $this->getYYB();
        $customerList = $this->getActiveCustomers($date);
        $positionList = $this->getAllPosition($date);
        if(!$customerList || !$positionList) {
            $query->status = 3;
            $query->save();
            return $this->ajax_return(500, '没找到数据');
        }
        $newPosition = [];
        $newPositionCompany = [];
        $exposure = [];  //敞口
        foreach($this->getList($positionList) as $v) {
            $newPosition[$v['KHH']][$v['HYPZ']] = $v['BZJ_JYS'];
            $newPositionCompany[$v['KHH']][$v['HYPZ']] = $v['BZJ'];
            $exposure[$v['KHH']][$v['HYPZ']] = $v['RATE'];
        }

        foreach($newPosition as $k => $v) {
            arsort($newPosition[$k]);
        }

        foreach($newPositionCompany as $k => $v) {
            arsort($newPositionCompany[$k]);
        }

        foreach($this->getList($customerList) as $val) {
            $khh = $val['KHH'];
            $zjzh = $val['ZJZH'];
            $khxm = $val['KHXM'];
            $khjl = $val['XM'];
            $sj = $val['SJ'];
            $jgbz = $val['KHLX']; // 这个字段改名了
            $yyb = trim($val['YYB']);
            if(isset($yybList[$yyb])) {
                $yyb = $yybList[$yyb];
            } else {
                $yyb = '暂无';
            }
            $sxf = $val['SXF'];
            $sxf_jys = $val['SXF_JYS'];
            $brjc = $val['BRJC'];
            $bzj = $val['BZJ'];
            $bzj_jys = $val['BZJ_JYS'];
            $bzj_rate = $brjc == 0?0:round($bzj/$brjc, 4);
            $jys_rate = $brjc == 0?0:round($bzj_jys/$brjc, 4);
            $pz1 = '';
            $pz2 = '';
            $pz3 = '';
            $pz1_v = 0;
            $pz2_v = 0;
            $pz3_v = 0;
            $pz1_rate = 0;
            $pz2_rate = 0;
            $pz3_rate = 0;

            $exp1 = 0;
            $exp2 = 0;
            $exp3 = 0;
            $exp1_c = 0;
            $exp2_c = 0;
            $exp3_c = 0;

            if(isset($newPosition[$khh]) && !empty($newPosition[$khh])) {
                $pzList = $newPosition[$khh];
                $index = 0;
                foreach($pzList as $k => $v) {
                    if($index == 3) break;
                    $par = 'pz'.($index+1);
                    $par_exp = 'exp'.($index+1);
                    $parV = $par.'_v';
                    $$par = $k;
                    $$parV = $v;
                    $$par_exp = $exposure[$khh][$k];
                    $index++;
                }
            }

            $pz1_rate = $bzj_jys == 0?0: round($pz1_v/$bzj_jys, 4);
            $pz2_rate = $bzj_jys == 0?0: round($pz2_v/$bzj_jys, 4);
            $pz3_rate = $bzj_jys == 0?0: round($pz3_v/$bzj_jys, 4);

            $pz1_c = '';
            $pz2_c = '';
            $pz3_c = '';
            $pz1_c_v = 0;
            $pz2_c_v = 0;
            $pz3_c_v = 0;
            $pz1_c_rate = 0;
            $pz2_c_rate = 0;
            $pz3_c_rate = 0;

            if(isset($newPositionCompany[$khh]) && !empty($newPositionCompany[$khh])) {
                $pzcList = $newPositionCompany[$khh];
                $index = 0;
                foreach($pzcList as $k => $v) {
                    $par_c = 'pz'.($index+1).'_c';
                    $par_exp_c = 'exp'.($index+1).'_c';
                    $parV_c = $par_c.'_v';
                    $$par_c = $k;
                    $$parV_c = $v;
                    $$par_exp_c = $exposure[$khh][$k];
                    $index++;
                }
            }
            $pz1_c_rate = $bzj == 0?0: round($pz1_c_v/$bzj, 4);
            $pz2_c_rate = $bzj == 0?0: round($pz2_c_v/$bzj, 4);
            $pz3_c_rate = $bzj == 0?0: round($pz3_c_v/$bzj, 4);

            $sql = "INSERT INTO `func_risk_degrees` ".
                " (`khh`, `khjl`, `zjzh`, `khxm`, `phone`, `jgbz`,".
                " `yyb`, `rq`, `sxf`, `sxf_jys`, `brjc`, ".
                " `bzj`, `bzj_jys`, `bzj_rate`, `jys_rate`,".
                " `pz1`, `pz1_rate`, `pz2`, `pz2_rate`, `pz3`, `pz3_rate`,".
                " `pz1_c`, `pz1_c_rate`, `pz2_c`, `pz2_c_rate`, `pz3_c`, `pz3_c_rate`,".
                " `exp1`,`exp2`,`exp3`,`exp1_c`,`exp2_c`,`exp3_c`".
                " ) VALUES ('{$khh}', '{$khjl}','{$zjzh}', '{$khxm}', '{$sj}', '{$jgbz}',".
                " '{$yyb}', '{$date}', '{$sxf}', '{$sxf_jys}', ".
                " '{$brjc}', '{$bzj}', '{$bzj_jys}', '{$bzj_rate}', '{$jys_rate}',".
                " '{$pz1}', '{$pz1_rate}', '{$pz2}', '{$pz2_rate}', '{$pz3}', '{$pz3_rate}',".
                " '{$pz1_c}', '{$pz1_c_rate}', '{$pz2_c}', '{$pz2_c_rate}', '{$pz3_c}', '{$pz3_c_rate}',".
                " '{$exp1}', '{$exp2}', '{$exp3}', '{$exp1_c}', '{$exp2_c}', '{$exp3_c}'".
                " )";
            $res = mysqli_query($oconn, $sql);
            if(!$res) {
                return $this->ajax_return(500, mysqli_error($oconn));
                $query->status = 3;
                $query->save();
            }
        }
        $query->status = 2;
        $query->save();
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 获取所有持仓
     * @return
     */
    public function getAllPosition($date){
        $sql = "select HYPZ,WTLB,sum(CCSL) as CCSL,sum(BZJ_JYS) as BZJ_JYS, sum(BZJ) as BZJ,KHH 
        from DCUSER.TFU_HYCCLS 
        where RQ = '{$date}' GROUP BY HYPZ,KHH,WTLB";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $list = $this->getCrmData($data);
        $newList = [];
        foreach($list as $k => $v) {
            if(!isset($newList[$v['KHH']][$v['HYPZ']])) {
                $newList[$v['KHH']][$v['HYPZ']] = [
                    'KHH' => $v['KHH'],
                    'BZJ_JYS' => 0,
                    'BZJ' => 0,
                    'jia' => 0,
                    'jian' => 0
                ];
            }
            $newList[$v['KHH']][$v['HYPZ']]['BZJ'] += $v['BZJ'];
            $newList[$v['KHH']][$v['HYPZ']]['BZJ_JYS'] += $v['BZJ_JYS'];
            if($v['WTLB'] == 1) {
                $newList[$v['KHH']][$v['HYPZ']]['jia'] += $v['CCSL'];
            } else {
                $newList[$v['KHH']][$v['HYPZ']]['jian'] += $v['CCSL'];
            }
        }
        $result = [];
        foreach($newList as $out) {
            foreach($out as $k => $v) {
                $total = $v['jia']+$v['jian'];
                $rate = $total == 0?0:abs(round(($v['jia']-$v['jian'])/$total, 4));
                $result[] = [
                    'HYPZ' => $k,
                    'RATE' => $rate,
                    'KHH' => $v['KHH'],
                    'BZJ_JYS' => $v['BZJ_JYS'],
                    'BZJ' => $v['BZJ']
                ];
            }
        }
        return $result;
    }


    /**
     * 获取营业部
     */
    public function getYYB(){
        $sql = "select ID,NAME from LBORGANIZATION";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $list = $this->getCrmData($data);
        $newList = [];
        foreach ($list as $v) {
            $newList[$v['ID']] = $v['NAME'];
        }
        return $newList;
    }

    /**
     * 查询活跃客户
     * @return mixed
     */
    public function getActiveCustomers($date){
        $select = " tyx.XM,kh.KHLX,kh.KHH,kh.YYB,kh.ZJZH,funcPFS_G_Decrypt(kh.SJ,'5a9e037ea39f777187d5c98b')SJ,kh.KHXM,zj.SXF,zj.SXF_JYS,zj.BRJC,zj.BZJ,zj.BZJ_JYS ";
        $sql = "select {$select} ".
            " from DCUSER.TFU_ZJQKLS zj".
            " left join TKHXX kh on kh.KHH = zj.KHH ".
            " left join (select ZJZH,min(GXR) GXR from TXCTC_YGKHGX group by ZJZH) a  on a.ZJZH = kh.ZJZH".
            " left join TXCTC_YGXX tyx on tyx.ID = a.GXR".
            " where zj.RQ = '{$date}' and zj.BZJ != 0";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $list = $this->getCrmData($data);
        return $list;
    }

}