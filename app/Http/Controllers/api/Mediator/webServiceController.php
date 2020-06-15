<?php

namespace App\Http\Controllers\api\Mediator;

use App\Http\Controllers\base\BaseController;

class webServiceController extends BaseController
{
    private $client = "";
    private $sessionId = "";

    public function __construct()
    {
        if(!$this->sessionId){
            $res = $this->login();
            if($res){
                $this->sessionId = $res;
            }else{
                die('登录失败！');
            }
        }
    }

    /**
     * 获取client
     * @return \SoapClient
     */
    private function getSoapClient()
    {
        $client = new \SoapClient("http://220.178.90.206:8088/service/LBEBusiness?wsdl");
        return $client;
    }

    /**
     * 登录
     * @return bool
     */
    private function login()
    {
        $this->client = $this->getSoapClient();
        $userId = "RPA";
        $password = "H@qh9772rpa,.";
        $scheme = "";
        $algorithm = "plain";
        $securityCode = "";
        $res = $this->client->login([
            'userid' => $userId,
            'password' => $password,
            'scheme' => $scheme,
            'algorithm' => $algorithm,
            'securityCode' => $securityCode,
        ]);
        $result = $res->LoginResult;
        if($result->result > 0){
            return $result->sessionId;
        }else{
            return false;
        }
    }

    /**
     * 退出登录
     * @return bool
     */
    public function logout()
    {
        $res = $this->client->logout([
            'sessionId' => $this->sessionId
        ]);
        $result = $res->LogoutResult;
        if($result->result > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 查询
     * @param $table
     * @param $where
     * @return mixed
     */
    private function query($table,$where)
    {
        $res = $this->client->query([
            'sessionId' => $this->sessionId,
            'objectName' => $table,
            'params' => null,
            'condition' => $where,
            'queryOption' => [
                'batchNo' => 1,
                'batchSize' => 10,
                'queryCount' => true,
                'valueOption' => 2
            ]
        ]);
        $result = $res->QueryResult;
        return $result->records->values;
    }

    /**
     * 审批流程
     * @param $instid
     * @return bool
     */
    public function doWorkAction($instid)
    {
        $res = $this->client->doWorkAction([
            'sessionId' => $this->sessionId,
            'workflowName' => 'WF_TXCTC_LC_JJR_XQSQ',
            'instanceId' => $instid,
            'actionId' => '132',
            'params' => '',
            'caller' => 'RPA',
            'summary' => '',
        ]);
        $result = $res->WorkActionResult;
        if($result->result > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取需要审批的流程
     * @return bool
     */
    public function queryTaskList()
    {
        $res = $this->client->queryTasks([
            'sessionId' => $this->sessionId,
            'caller' => '227',
            'queryOption' => [
                'batchNo' => 1,
                'batchSize' => 10,
                'queryCount' => true,
                'valueOption' => 1
            ]
        ]);
        $result = $res->QueryResult;
        if($result->result > 0){
            if($result->count >0){
                return $result->records;
            }else{
                return 0;
            }
        }else{
            return -1;
        }
    }
}
?>