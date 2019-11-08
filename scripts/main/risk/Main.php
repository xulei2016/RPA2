<?php
/**
 * 风险度和保证金查询
 */

require_once "../../../vendor/autoload.php";

use GuzzleHttp\Client;

class Main {

    public $url = 'www.localhost.com:9102/index.php';

    public $date;

    public $localConn; // 数据库连接

    public $khTable = 'func_risk_khs'; // 客户表

    public $degreeTable = 'func_risk_degrees'; // 风险度表

    public $positionTable = 'func_risk_positions'; // 集中度表
    
    public $queryTable = 'func_risk_querys'; // 查询记录表

    public function __construct($date = false)
    {
        if($date) {
            $date = date('Ymd', strtotime($date));
        } else {
            $date = date('Ymd', strtotime('-1 day'));
        }
        $this->date = $date;
        $this->getLocalConn();
    }

    /**
     * 获取数据库连接
     */
    public function getLocalConn(){
        $ohost = "localhost";
        $ouser = "mycat";
        $opwd = "mycat";
        $odb = "rpa";
        $port = 8066;
        $oconn = mysqli_connect($ohost,$ouser,$opwd,$odb,$port);
        $this->localConn = $oconn;
    }


    /**
     * 查询活跃客户
     * @return mixed
     */
    public function getActiveCustomers(){
        $select = " tyx.XM,kh.KHH,kh.YYB,kh.ZJZH,funcPFS_G_Decrypt(kh.SJ,'5a9e037ea39f777187d5c98b')SJ,kh.KHXM,zj.SXF,zj.SXF_JYS,zj.BRJC,zj.BZJ,zj.BZJ_JYS ";
        $sql = "select {$select} ".
        " from DCUSER.TFU_ZJQKLS zj".
        " left join TKHXX kh on kh.KHH = zj.KHH ".
        " left join (select ZJZH,min(GXR) GXR from TXCTC_YGKHGX group by ZJZH) a  on a.ZJZH = kh.ZJZH". 
	    " left join TXCTC_YGXX tyx on tyx.ID = a.GXR".
        " where zj.RQ = '{$this->date}' and zj.BZJ != 0";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $list = $this->getData($data);
        return $list;
    }

    public function getList($list){
        foreach($list as $v) {
            yield $v;
        }
    }

    public function run(){
        $this->log("开始时间",date('Y-m-d H:i:s'));
        $customerList = $this->getActiveCustomers();
        $positionList = $this->getAllPosition();
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



        foreach($this->getList($customerList) as $k => $v) {
            $khh = $v['KHH'];
            $zjzh = $v['ZJZH'];
            $khxm = $v['KHXM'];
            $khjl = $v['XM'];
            $sj = $v['SJ'];
            $yyb = $v['YYB'];
            $sxf = $v['SXF'];
            $sxf_jys = $v['SXF_JYS'];
            $brjc = $v['BRJC'];
            $bzj = $v['BZJ'];
            $bzj_jys = $v['BZJ_JYS'];
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
            " (`khh`, `khjl`, `zjzh`, `khxm`, `phone`, ".
            " `yyb`, `rq`, `sxf`, `sxf_jys`, `brjc`, ".
            " `bzj`, `bzj_jys`, `bzj_rate`, `jys_rate`,". 
            " `pz1`, `pz1_rate`, `pz2`, `pz2_rate`, `pz3`, `pz3_rate`,".
            " `pz1_c`, `pz1_c_rate`, `pz2_c`, `pz2_c_rate`, `pz3_c`, `pz3_c_rate`,".
            " `exp1`,`exp2`,`exp3`,`exp1_c`,`exp2_c`,`exp3_c`".
            " ) VALUES ('{$khh}', '{$khjl}','{$zjzh}', '{$khxm}', '{$sj}',".
            " '{$yyb}', '{$this->date}', '{$sxf}', '{$sxf_jys}', ".
            " '{$brjc}', '{$bzj}', '{$bzj_jys}', '{$bzj_rate}', '{$jys_rate}',". 
            " '{$pz1}', '{$pz1_rate}', '{$pz2}', '{$pz2_rate}', '{$pz3}', '{$pz3_rate}',".
            " '{$pz1_c}', '{$pz1_c_rate}', '{$pz2_c}', '{$pz2_c_rate}', '{$pz3_c}', '{$pz3_c_rate}',".
            " '{$exp1}', '{$exp2}', '{$exp3}', '{$exp1_c}', '{$exp2_c}', '{$exp3_c}'".
            " )";
            $res = mysqli_query($this->localConn, $sql);
            if(!$res) {
                $this->log("失败khh---$khh", $sql);
            }
        }
        $this->log("结束时间", date('Y-m-d H:i:s'));
    }

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
        $list = $this->getData($data);
        return $list;
    }

    /**
     * 获取所有的持仓
     */
    public function getAllPosition(){
        $sql = "select HYPZ,WTLB,sum(CCSL) as CCSL,sum(BZJ_JYS) as BZJ_JYS, sum(BZJ) as BZJ,KHH 
        from DCUSER.TFU_HYCCLS 
        where RQ = '{$this->date}' GROUP BY HYPZ,KHH,WTLB";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $list = $this->getData($data);
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
     * 获取所有的持仓
     */
    public function getAllPositionBak(){
        $sql = "select HYPZ,sum(BZJ_JYS) as BZJ_JYS, sum(BZJ) as BZJ,KHH 
        from DCUSER.TFU_HYCCLS where RQ = '{$this->date}' 
        GROUP BY HYPZ,KHH";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $sql
            ]
        ];
        $list = $this->getData($data);
        return $list;
    }


    /**
     * 获取数据
     * @param $data
     * @return mixed
     */
    public function getData($data){
        $guzzle = new Client();
        $response = $guzzle->post($this->url, [
            'form_params' => $data,
            'synchronous' => true,
            'timeout' => 0,
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        if(is_array($result)) {
            return $result;
        } else {
            return [];
        }
    }

    
    public function log($name, $insert){
        $content = "[{$name}]--[{$insert}];\r\n";
        file_put_contents("./log/{$this->date}.log", $content, FILE_APPEND);
    }
}
$date = "2019-09-24";
$date = date('Ymd', strtotime($date));
$main = new Main($date);
$list = $main->getYYB();
$filename = "./yyb.csv";
foreach($list as $k => $v) {
    file_put_contents($filename, "{$v['ID']},{$v['NAME']}\r\n", FILE_APPEND);
}