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
        $select = " kh.KHH,kh.YYB,kh.ZJZH,funcPFS_G_Decrypt(kh.SJ,'5a9e037ea39f777187d5c98b')SJ,kh.KHXM,zj.SXF,zj.SXF_JYS,zj.BRJC,zj.BZJ,zj.BZJ_JYS ";
        $sql = "select {$select} ".
        " from TKHXX kh left join DCUSER.TFU_ZJQKLS zj on kh.KHH = zj.KHH ".
        " where zj.RQ = '{$this->date}' and zj.BZJ != 0 and kh.KHZT = 0 and kh.FXYS = '5'";
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

    public function other(){
        $customerList = $this->getActiveCustomers();
        $positionList = $this->getAllPosition();
        $newPosition = [];
        foreach($this->getList($positionList) as $v) {
            $newPosition[$v['KHH']][$v['HYPZ']] = $v['BZJ_JYS'];
        }

        foreach($newPosition as $k => $v) {
            arsort($newPosition[$k]);
        }

        foreach($this->getList($customerList) as $k => $v) {
            $khh = $v['KHH'];
            $zjzh = $v['ZJZH'];
            $khxm = $v['KHXM'];
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
            if(isset($newPosition[$khh]) && !empty($newPosition[$khh])) {
                $pzList = $newPosition[$khh];
                $index = 0;
                foreach($pzList as $k => $v) {
                    $par = 'pz'.($index+1);
                    $parV = $par.'_v';
                    $$par = $k;
                    $$parV = $v;
                    $index++;
                }
            } 
            $pz1_rate = $bzj_jys == 0?0: round($pz1_v/$bzj_jys, 4);
            $pz2_rate = $bzj_jys == 0?0: round($pz2_v/$bzj_jys, 4);
            $pz3_rate = $bzj_jys == 0?0: round($pz3_v/$bzj_jys, 4);
            $sql = "INSERT INTO `func_risk_degrees` (`khh`, `zjzh`, `khxm`, `phone`, `yyb`, `rq`, `sxf`, `sxf_jys`, `brjc`, `bzj`, `bzj_jys`, `bzj_rate`, `jys_rate`, `pz1`, `pz1_rate`, `pz2`, `pz2_rate`, `pz3`, `pz3_rate`)".
            "VALUES ('{$khh}', '{$zjzh}', '{$khxm}', '{$sj}', '{$yyb}', '{$this->date}', '{$sxf}', '{$sxf_jys}', '{$brjc}', '{$bzj}', '{$bzj_jys}', '{$bzj_rate}', '{$jys_rate}', '{$pz1}', '{$pz1_rate}', '{$pz2}', '{$pz2_rate}', '{$pz3}', '{$pz3_rate}')";
            $res = mysqli_query($this->localConn, $sql);
            if(!$res) {
                $this->log("存储失败khh=$khh", $sql);
            }
        }
    
    }

   

    /**
     * 运行主方法
     */
    public function run(){
        $this->log("开始时间", date('Y-m-d H:i:s'));
        $this->other();
        $this->log("结束时间", date('Y-m-d H:i:s'));
        die();
        $rq = $this->date;
        $sql = "insert into {$this->queryTable} (rq,status) values ('{$rq}',1)";
        mysqli_query($this->localConn, $sql);
        $customerList = $this->getActiveCustomers();
        foreach ($customerList as $k => $v) {
            $id = $this->saveKh($v);
            $this->saveRiskDegree($id, $v);
            $this->position($id, $v['KHH'], $v['BZJ_JYS']);
        }
        $sql = "update ($this->queryTable) set status = 2 where rq = '$rq'";
        mysqli_query($this->localConn, $sql);
    }

    /**
     * 保存风险度
     * @param $id
     * @param $info
     */
    public function saveRiskDegree($id, $info){
        $rq = $this->date;
        $sql = "select id from {$this->degreeTable} where kh_id = {$id} and rq = '{$rq}'";
        $res = mysqli_query($this->localConn, $sql);
        $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
        if(!empty($row)) return false;
        $sxf = $info['SXF'];
        $sxf_jys = $info['SXF_JYS'];
        $brjc = $info['BRJC'];
        $bzj = $info['BZJ'];
        $bzj_jys = $info['BZJ_JYS'];
        $bzj_rate = $brjc == 0 ? 0 : round($bzj/$brjc, 4);
        $jys_rate = $brjc == 0 ? 0 : round($bzj_jys/$brjc, 4);
        $insert = "insert into {$this->degreeTable} (kh_id,rq,sxf,sxf_jys,brjc,bzj,bzj_jys,bzj_rate,jys_rate) values ($id,'{$rq}','{$sxf}','{$sxf_jys}','{$brjc}','{$bzj}','{$bzj_jys}','{$bzj_rate}','{$jys_rate}')";
        $res = mysqli_query($this->localConn, $insert);
        if(!$res) {
            $this->log('风险度', $insert);
        }
    }

    /**
     * 持仓集中度
     * @param $id
     * @param $khh
     */
    public function position($id, $khh, $bzj_jys_total){
        $rq = $this->date;
        $sql = "select id from {$this->positionTable} where kh_id = {$id} and rq = '{$rq}'";
        $res = mysqli_query($this->localConn, $sql);
        $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
        if(!empty($row)) return false;
        $select = "JYS,HYPZ,HYDM,WTLB,TZLB,CCSL,CCJG,BZJ,BZJ_JYS";
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => "select {$select} from DCUSER.TFU_HYCCLS where RQ = '$rq' and KHH = '$khh'"
            ]
        ];
        $list = $this->getData($data);
        foreach($list as $k => $v) {
            $jys = $v['JYS'];
            $hypz = $v['HYPZ'];
            $hydm = $v['HYDM'];
            $wtlb = $v['WTLB'];
            $tzlb = $v['TZLB'];
            $ccsl = $v['CCSL'];
            $ccjg = $v['CCJG'];
            $bzj = $v['BZJ'];
            $bzj_jys = $v['BZJ_JYS'];
            $jys_rate = $bzj_jys_total == 0 ? 0 : round($bzj_jys/$bzj_jys_total, 4);
            $insert = "insert into {$this->positionTable} (kh_id,rq,jys,hypz,hydm,wtlb,tzlb,ccsl,ccjg,bzj,bzj_jys,jys_rate) values ($id,'{$rq}','{$jys}','{$hypz}','{$hydm}','{$wtlb}','{$tzlb}','{$ccsl}','{$ccjg}','{$bzj}','{$bzj_jys}','{$jys_rate}')";
            $res = mysqli_query($this->localConn, $insert);
            if(!$res) {
                $this->log('持仓集中度', $insert);
            }
        }
        return $list;
    }

    /**
     * 保存客户信息
     * @param $id
     * @param $info
     * @return mixed
     */
    public function saveKh($info){
        $khh = $info['KHH'];
        $zjzh = $info['ZJZH'];
        $khxm = $info['KHXM'];
        $phone = $info['SJ'];
        $yyb = $info['YYB'];
        $sql = "select id from {$this->khTable} where khh = '$khh'";
        $res = mysqli_query($this->localConn, $sql);
        $row = mysqli_fetch_all($res, MYSQLI_ASSOC);
        if(empty($row)) {
            $insert = "insert into {$this->khTable} (khh,zjzh,khxm,yyb,phone) values ('{$khh}','{$zjzh}','{$khxm}','{$yyb}','{$phone}')";
            $res = mysqli_query($this->localConn, $insert);
            if(!$res) {
                $this->log('客户表', $insert);
            }
            $id = mysqli_insert_id($this->localConn);
        } else {
            $id = $row[0]['id'];
        }
        return $id;
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
$date = "2019-09-23";
$date = date('Ymd', strtotime($date));
$main = new Main($date);
$main->run();