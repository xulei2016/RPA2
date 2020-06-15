<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\base\BaseWebController;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_jjrcredit_sxstates;
use App\Models\Index\Common\FuncLostCreditRecord;
use App\Models\Index\Common\FuncLostCreditShowRecord;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorFlow;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * 失信查询控制器
 * Class BrokenPromisesController
 * @package App\Http\Controllers\Index
 */
class LoseCreditController extends BaseWebController
{

    const TASK_STATUS_WAIT = 0; // 查询中
    const TASK_STATUS_NO_LOST_CREDIT= 1; // 无失信
    const TASK_STATUS_LOST_CREDIT = 2; // 失信
    const TASK_STATUS_FAIL = 3; // 查询失败

    const TIME_OUT = 600; // 任务超时时间

    public $view_prefix = "Index.LoseCredit.";

    public function __construct(){
        // echo "因恒生账户系统维护,该功能暂时无法使用,预计晚上9点恢复";die;
    }

    /**
     * 图片类型
     * @var array
     */
    private $imageTypeList = [
        'sf' => '证券业失信截图',
        'cfa' => '期货业失信截图',
        'xyzg' => '信用中国截图',
        'hs' => '恒生黑名单截图',
        'zxgk' => '执行信息公开网截图',
        'gsxt' => '国家企信网截图',
    ];

    /**
     * 类型列表
     * @var array
     */
    private $typeList = [
        'person' => '个人',
        'company' => '企业',
        'legalPerson' => '法人',
        'agentPerson' => '授权代理人',
    ];

    /**
     * 首页
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = [
            'idCard' => '',
            'number' => '',
            'name' => '',
        ];
        return view($this->view_prefix.'index', ['data' => $data]);
    }

    /**
     * 首页
     * @param Request $request
     * @return mixed
     */
    public function otherIndex(Request $request, $id)
    {
        $data = [
            'idCard' => '',
            'number' => '',
            'name' => '',
        ];
        $flow = FuncMediatorFlow::where('number', $id)->first();
        if($flow) {
            $info = FuncMediatorInfo::find($flow->uid);
            if($info) {
                $data['idCard'] = $info->zjbh = substr_replace($info->zjbh, "********", 6, 8);
                $data['number'] = $flow->number;
                $data['name'] = $info->name;
            }
        }
        
        return view($this->view_prefix.'index', ['data' => $data]);
    }

    /**
     * 提交查询
     * @param Request $request
     * @return mixed
     */
    public function query(Request $request)
    {
        $currentDate = date('Y-m-d');
        $dataList = [];

        if(isset($request->pName)) //自然人
        {
            if($request->number) { //
                $flow = FuncMediatorFlow::where('number', $request->number)->first();
                $info = FuncMediatorInfo::find($flow->uid);
                $request->pCard = $info->zjbh;
                $request->pName = $info->name;
            }
            $request->pCard = strtoupper($request->pCard);
            if(!validation_filter_id_card($request->pCard)) {
                return $this->ajax_return(500, '请输入正确的身份证');
            }
            $where = [
                'name' => $request->pName,
                'idCard' => $request->pCard,
                'type' => '1'
            ];
            $item = $where;
            $item['kind'] = 'person';
            $dataList[] = $item;
        }
        if(isset($request->cName)) // 公司
        {
            $where = [
                'name' => $request->cName,
                'idCard' => $request->cCard,
                'type' => '2',
            ];
            $item = $where;
            $item['kind'] = 'company';
            $dataList[] = $item;
        }
        if(isset($request->fName)) // 法人{
        {
            $request->fCard = strtoupper($request->fCard);
            if(!validation_filter_id_card($request->fCard)) {
                return $this->ajax_return(500, '请输入正确的身份证');
            }
            $dataList[] = [
                'name' => $request->fName,
                'idCard' => $request->fCard,
                'type' => '1',
                'kind' => 'legalPerson',
            ];
        }
        if(isset($request->dName)) //授权代理人
        {
            $request->dCard = strtoupper($request->dCard);
            if(!validation_filter_id_card($request->dCard)) {
                return $this->ajax_return(500, '请输入正确的身份证');
            }
            $dataList[] = [
                'name' => $request->dName,
                'idCard' => $request->dCard,
                'type' => '1',
                'kind' => 'agentPerson'
            ];
        }

        if($request->type === 'person') { // 个人
            $personRecord = FuncLostCreditRecord::where($where)
                ->where([
                    ['date', $currentDate],
                    ['status', 2]
                ])->first();
            if($personRecord) {
                return $this->ajax_return(201, $personRecord->code);
            }
        }

        $where['date'] = $currentDate;
        $record = FuncLostCreditRecord::create($where);

        $code = $this->getUUID($record->id); // 获取唯一识别码

        foreach ($dataList as $k => &$v)
        {
            $condition = $v;
            $kind = $condition['kind']; // 类型
            if($kind == 'agentPerson') { // 授权代理人
                if($v['idCard'] == $dataList[$k-1]['idCard']) { // 代理人和法人为同一人
                    $v['status'] = $dataList[$k-1]['status'];
                    continue;
                }
            }
            unset($condition['kind']);
            $v['status'] = 0;
            $condition['quedate'] = $currentDate; // 查询条件增加日期
            $queryResult = rpa_jjrcredit_sxstates::where($condition)
                ->orderBy('id', 'desc')->first();
            if($queryResult) {  // 有结果
                $taskStatus = $this->judgeResult($queryResult);
                if(($taskStatus == self::TASK_STATUS_FAIL || $taskStatus == self::TASK_STATUS_LOST_CREDIT) && $request->resetQuery) {
                    $this->sendTask($v, $code);
                    $this->changeStatus($queryResult);
                    $taskStatus = self::TASK_STATUS_WAIT;
                }
                $v['status'] = $taskStatus;
            } else {
                // 发送一条任务
                $condition['created_at'] = date('Y-m-d H:i:s');
                rpa_jjrcredit_sxstates::create($condition);
                $this->sendTask($v, $code);
            }
        }

        $record->status = 1;
        $record->code = $code;
        $record->data = json_encode($dataList, JSON_UNESCAPED_UNICODE);
        $record->save();
        return $this->ajax_return(200, '', $code);
    }

    /**
     * 更改状态
     * @param $result
     */
    public function changeStatus($result){
        foreach ($this->imageTypeList as $k => $v) {
            $field = $k.'state';
            if($result->$field == -1 || $result->$field == 1) { // 查询失败和失信
                $result->$field = null;

            }
        }
        $result->created_at = date('Y-m-d H:i:s');
        $result->save();
    }

    /**
     * 循环查询
     * @param Request $request
     * @return array
     */
    public function loopQuery(Request $request)
    {
        $code = $request->code;
        $record = FuncLostCreditRecord::where(['code' => $code])->first();
        if(!$record) {
            return $this->ajax_return(500, '', '查询异常,请重试');
        }
        $currentDate = date('Y-m-d');
        $dataList = json_decode($record->data, true);
        $resultList = [];
        foreach ($dataList as &$data) {
            $result = rpa_jjrcredit_sxstates::where([
                'name' => $data['name'],
                'idCard' => $data['idCard'],
                'type' => $data['type'],
                'quedate' => $currentDate
            ])->orderBy('id', 'desc')->first();
            if($result) {
                $taskStatus = $this->judgeResult($result);
            } else { // 没有找到记录
                $taskStatus = self::TASK_STATUS_WAIT;
            }
            $data['status'] = $taskStatus;
            $resultList[] = $taskStatus;
        }
        $record->data = json_encode($dataList, JSON_UNESCAPED_UNICODE);
        $record->save();
        if(in_array(self::TASK_STATUS_LOST_CREDIT, $resultList)) {
            $record->status = 3;
            $record->save();
            return $this->ajax_return(201, '存在失信记录');
        } elseif(in_array(self::TASK_STATUS_FAIL, $resultList)) {
            $record->status = 4;
            $record->save();
            return $this->ajax_return(500, '查询失败');
        }  elseif(in_array(self::TASK_STATUS_WAIT, $resultList)) {
            return $this->ajax_return(202, '查询中,请稍后');
        } else {
            $record->status = 2;
            $record->save();
            $this->submitToCrm($dataList, $code);
            return $this->ajax_return(200, '无失信记录');
        }

    }


    /**
     * 展示失信查询
     * @param Request $request
     * @return mixed
     * @desc $status // 0 参数不全 1 验证成功  2 验证失败
     * promiseID=123456&TOKEN=2333&FlowID=1&userID=admin&userName=admin
     */
    public function showLostCreditBak(Request $request)
    {
        $newList = [];
        try{
            $request->validate([
                'promiseID' => 'required',
                'FlowID' => 'required',
                'TOKEN' => 'required',
                'userID' => 'required',
                'userName' => 'required',
            ]);
            // 发送一个crm查询
            $data = $request->all();
            $result = $this->validateCrm($data);
            if($result) {
                $uuid = $data['promiseID'];
                $status = 1;
                $info = FuncLostCreditRecord::where([
                    ['code', $uuid],
                    ['status', 2]
                ])->first();

                if(!$info) {
                    $status = 2;
                } else {
                    $dataList = json_decode($info->data, true);
                    $currentDate = $info->date;
                    
                    $legalName = '';
                    $legalCard = '';
                    foreach ($dataList as &$item) {
                        if($item['kind'] == 'legalPerson') {
                            $legalName = $item['name'];
                            $legalCard = $item['card'];
                        }
                        if($item['kind'] == 'agentPerson') { 
                            // 代理人
                            if($legalName == $item['name'] && $legalCard == $item['card']) {
                                continue;
                            }

                        }
                        $jjr = rpa_jjrcredit_sxstates::where([
                            ['name', $item['name']],
                            ['idCard', $item['idCard']],
                            ['type', $item['type']],
                            ['quedate', $currentDate],
                        ])->first()->toArray();
                        $img = $this->handleImg($jjr);
                        $newList[] = [
                            'name' => $this->typeList[$item['kind']],
                            'type' => $item['kind'],
                            'list' => $img
                        ];
                    }
                }
            } else {
                $status = 2;
            }
            FuncLostCreditShowRecord::create([
                'promise_id' => $data['promiseID'],
                'flow_id' => $data['FlowID'],
                'token' => $data['TOKEN'],
                'user_id' => $data['userID'],
                'user_name' => $data['userName'],
                'status' => $status
            ]);
        } catch (ValidationException $e) {
            $status = 0;
        }
        return view($this->view_prefix.'show', [
            'status' => $status,
            'list' => $newList
        ]);
    }

/**
     * 展示失信查询
     * @param Request $request
     * @return mixed
     * @desc $status // 0 参数不全 1 验证成功  2 验证失败
     * promiseID=123456&TOKEN=2333&FlowID=1&userID=admin&userName=admin
     */
    public function showLostCredit(Request $request)
    {
        $newList = [];
        try{
            $request->validate([
                'promiseID' => 'required',
                'FlowID' => 'required',
                'TOKEN' => 'required',
                'userID' => 'required',
                'userName' => 'required',
            ]);
            // 发送一个crm查询
            $data = $request->all();
            $result = $this->validateCrm($data);
            if($result) {
                $uuid = $data['promiseID'];
                $status = 1;
                $info = FuncLostCreditRecord::where([
                    ['code', $uuid],
                    ['status', 2]
                ])->first();
                if(!$info) {
                    $status = 2;
                } else {
                    $dataList = json_decode($info->data, true);
                    $currentDate = $info->date;
                    
                    $legalName = '';
                    $legalCard = '';
                    foreach ($dataList as &$item) {
                        if($item['kind'] == 'legalPerson') {
                            $legalName = $item['name'];
                            $legalCard = $item['idCard'];
                        }
                        if($item['kind'] == 'agentPerson') { 
                            // 代理人
                            if($legalName == $item['name'] && $legalCard == $item['idCard']) {
                                continue;
                            }

                        }
                        $jjr = rpa_jjrcredit_sxstates::where([
                            ['name', $item['name']],
                            ['idCard', $item['idCard']],
                            ['type', $item['type']],
                            ['quedate', $currentDate],
                        ])->first()->toArray();
                        $img = $this->handleImg($jjr);
                        $newList[] = [
                            'name' => $this->typeList[$item['kind']],
                            'type' => $item['kind'],
                            'list' => $img
                        ];
                    }
                }
            } else {
                $status = 2;
            }
            FuncLostCreditShowRecord::create([
                'promise_id' => $data['promiseID'],
                'flow_id' => $data['FlowID'],
                'token' => $data['TOKEN'],
                'user_id' => $data['userID'],
                'user_name' => $data['userName'],
                'status' => $status
            ]);
        } catch (ValidationException $e) {
            $status = 0;
        }
        return view($this->view_prefix.'show', [
            'status' => $status,
            'list' => $newList
        ]);
    }

    /**
     * 展示图片
     * @param Request $request
     */
    public function showImg(Request $request){
        $url = $request->url;
        $url = decrypt($url);
        $info = getimagesize($url);
        $mime = $info['mime'];
        header("Content-type:$mime");
        echo file_get_contents($url);
    }
    
    // 以下是内部方法

    /**
     * 判断任务结果
     * @param $data
     * @return int
     */
    private function judgeResult($data)
    {
        $data = $data->toArray();
        $checkList = ['sfstate', 'cfastate', 'xyzgstate', 'hsstate', 'zxgkstate'];
        if($data['type'] === '2') { // 法人多一个 国家企信网
            $checkList[] = 'gsxtstate';
        }
        $newList = [];
        foreach ($checkList as $check) {
            // 1失信  0 正常  -1 失败 2 暂无结果
            $newList[] = is_null($data[$check])?'2':$data[$check];
        }
        if(in_array('1', $newList)) {
            $taskStatus = self::TASK_STATUS_LOST_CREDIT;
        } elseif(in_array('-1', $newList)) { // 查询失败
            $taskStatus = self::TASK_STATUS_FAIL;
        } elseif(in_array('2', $newList)) { // 查询中 需要格外判断时间
            if(time()-strtotime($data['created_at'])>self::TIME_OUT) { // 超时 查询失败
                $taskStatus = self::TASK_STATUS_FAIL;
            } else {
                $taskStatus = self::TASK_STATUS_WAIT;
            }
        } else {
            $taskStatus = self::TASK_STATUS_NO_LOST_CREDIT;
        }
        return $taskStatus;
    }

    /**
     * 获取识别吗  20200101000001
     * @param $id
     * @return string
     */
    private function getUUID($id)
    {
        $number = sprintf("%06d",$id);
        return date('Ymd').$number;
    }

    /**
     * 发送任务
     * @param $data
     * @param $code
     */
    private function sendTask($data, $code)
    {
        $data['tid'] = $code;
        unset($data['status']);
        $add = [
            'name' => 'Supervision_jjr',
            'jsondata' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s')
        ];
        rpa_immedtasks::create($add);
    }

    /**
     * 查询crm数据
     * @param $data
     * @return boolean
     */
    private function validateCrm($data)
    {
        $param = [
            'type' => 'credit',
            'action' => 'validateCode',
            'param' => [
                'uuid' => $data['promiseID'],
                'token' => $data['TOKEN'],
            ]
        ];
        $result = $this->getCrmData($param);
        if($result) {
            if($result['code'] == 200) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 提交数据到crm
     * @param $list
     * @param $code
     */
    private function submitToCrm($list, $code)
    {
        $data = [
            'JJR_MC' => "",
            'JJR_BH' => "",
            'JJR_DLR_MC' => "",
            'JJR_DLR_BH' => "",
            'JJR_FR_MC' => "",
            'JJR_FR_BH' => "",
        ];
        foreach ($list as $v) {
            if($v['kind'] == 'person' || $v['kind'] == 'company') {
                $data['JJR_MC'] = $v['name'];
                $data['JJR_BH'] = $v['idCard'];
            } elseif($v['kind'] == 'legalPerson') { // 法人
                $data['JJR_FR_MC'] = $v['name'];
                $data['JJR_FR_BH'] = $v['idCard'];
            } elseif($v['kind'] == 'agentPerson') { // 代理人
                $data['JJR_DLR_MC'] = $v['name'];
                $data['JJR_DLR_BH'] = $v['idCard'];
            }
        }

        if($data['JJR_FR_MC'] == $data['JJR_DLR_MC'] && $data['JJR_FR_BH'] == $data['JJR_DLR_BH']) { // 同一人
            $data['JJR_DLR_MC'] = '';
            $data['JJR_DLR_BH'] = '';
        }

        $param = [
            'type' => 'credit',
            'action' => 'submitRecord',
            'param' => [
                'uuid' => $code,
                'data' => $data
            ]
        ];
        $result = $this->getCrmData($param);
    }


    /**
     * 处理照片数组
     * @param $data
     * @return array
     */
    private function handleImg($data)
    {
        $dataList = [];
        foreach ($this->imageTypeList as $key =>  $v) {
            $url = $data[$key.'paths']; // 多个用逗号隔开
            if($url) {
                $urls = explode(',', $url);
                foreach ($urls as $u) {
                    $dataList[] = [
                        'name' => $v,
                        'url' => encrypt($u)
                    ];
                }
            }
        }
        return $dataList;
    }

}