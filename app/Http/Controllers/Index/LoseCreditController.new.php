<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\base\BaseWebController;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_jjrcredit_sxstates;
use App\Models\Index\Common\FuncLostCreditRecord;
use App\Models\Index\Common\FuncLostCreditShowRecord;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

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

    private $viewPrefix = "Index.LoseCredit.";

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
        return view($this->viewPrefix.'index');
    }

    /**
     * 递交查询
     * @param Request $request
     * @return mixed
     * @desc type 1 人  2 公司
     */
    public function query(Request $request)
    {
        $currentDate = date('Y-m-d');
        $dataList = [];
        $condition = [];
        $type = 1;
        if(isset($request->pName)) //自然人
        {
            $this->validationIdCard($request->pCard);
            $condition[] = ['name', '=', $request->pName];
            $condition[] = ['id_card', '=', $request->pCard];
            $dataList[] = [
                'name' => $request->pName,
                'idCard' => $request->pCard,
                'type' => 1,
                'kind' => 'person'
            ];
        }
        if(isset($request->cName)) // 公司
        {
            $type = 2;
            $condition[] = ['name', '=', $request->cName];
            $condition[] = ['id_card', '=', $request->cCard];
            $dataList[] = [
                'name' => $request->cName,
                'idCard' => $request->cCard,
                'type' => 2,
                'kind' => 'company'
            ];
        }
        if(isset($request->lName)) // 法人
        {
            $this->validationIdCard($request->lCard);
            $condition[] = ['legal_person_name', '=', $request->lName];
            $condition[] = ['legal_person_card', '=', $request->lCard];
            $dataList[] = [
                'name' => $request->lName,
                'idCard' => $request->lCard,
                'type' => 1,
                'kind' => 'legalPerson',
            ];
        }
        if(isset($request->aName)) //授权代理人
        {
            $this->validationIdCard($request->aCard);
            $condition[] = ['agent_person_name', '=', $request->aName];
            $condition[] = ['agent_person_card', '=', $request->aCard];
            $dataList[] = [
                'name' => $request->aName,
                'idCard' => $request->aCard,
                'type' => 1,
                'kind' => 'agentPerson'
            ];
        }

        $condition[] = ['type', '=', $type];
        $condition[] = ['is_valid', '=', 1];
        $lostCreditRecord = FuncLostCreditRecord::where($condition)
            ->where('date', '=', $currentDate)
            ->orderBy('id', 'desc')->first();

        if($lostCreditRecord) {
            if(isset($request->resetQuery)) {
                $lostCreditRecord->is_valid = 0;
                $lostCreditRecord->save();
            } else {
                $status = $lostCreditRecord->status;
                if($status == 1) { //正在查询的记录需要判断是否超时
                    if(time()-strtotime($lostCreditRecord->created_at) > self::TIME_OUT) { // 超时当查询失败处理
                        $status = 4;
                    }
                }
                return $this->ajax_return(201, '已有相似查询记录', [
                    'code' => $lostCreditRecord->code,
                    'status' => $status,
                    'token' => $lostCreditRecord->token
                ]);
            }

        }
        // 构建数据库表单字段
        $saveData = [
            'date' => $currentDate,
        ];
        foreach ($condition as $item) {
            $saveData[$item[0]] = $item[2];
        }
        $saveData['date'] = $currentDate;
        $record = FuncLostCreditRecord::create($saveData);
        $uuid = $this->getUUID($record->id); // 获取唯一识别码
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
            if($queryResult) {  //有任务查询记录
                $taskStatus = $this->judgeResult($queryResult);
                if($taskStatus === self::TASK_STATUS_WAIT) {
                    return $this->ajax_return(500, '正在查询中， 请稍后查询结果');
                }
                $v['status'] = $taskStatus;
            } else { // 写入数据库并发布任务
                // 发送一条任务
                $condition['created_at'] = date('Y-m-d H:i:s');
                rpa_jjrcredit_sxstates::create($condition);
                $this->sendTask($v, $uuid);
            }
        }

        $record->status = 1;
        $record->code = $uuid;
        $record->data = json_encode($dataList, JSON_UNESCAPED_UNICODE);
        $record->save();
        return $this->ajax_return(200, '', [
            'code' => $uuid
        ]);
    }

    /**
     * 验证身份证
     * @param $card
     * @return mixed
     */
    public function validationIdCard($card){
        if(!validation_filter_id_card($card)) {
            return $this->ajax_return(500, '请输入正确的身份证');
        }
        return true;
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
            return $this->ajax_return(500, '查询异常,请重试');
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
                if($taskStatus == self::TASK_STATUS_FAIL) { //任务失败  重新发送一条任务
                    $this->sendTask($data, $record->code);
                    $taskStatus = self::TASK_STATUS_WAIT;
                }
            } else { // 没有找到记录
                $taskStatus = self::TASK_STATUS_WAIT;
            }
            $data['status'] = $taskStatus;
            $resultList[] = $taskStatus;
        }
        $record->data = json_encode($dataList, JSON_UNESCAPED_UNICODE);
        $record->save();
        $token = $this->createToken();
        if(in_array(self::TASK_STATUS_LOST_CREDIT, $resultList)) {
            $record->status = 3;
            $record->token = $token;
            $record->save();
            return $this->ajax_return(201, '存在失信记录', [
                'code' => $code,
                'token' => $token,
            ]);
        } elseif(in_array(self::TASK_STATUS_WAIT, $resultList)) {
            return $this->ajax_return(202, '查询中,请稍后');
        } else {
            $record->status = 2;
            $record->token = $token;
            $record->save();
           $this->submitToCrm($dataList, $code);
            return $this->ajax_return(200, '无失信记录', [
                'code' => $code,
                'token' => $token,
            ]);
        }

    }

    /**
     * 展示失信查询来自crm
     * @param Request $request
     * @return mixed
     * @desc $status // 0 参数不全 1 验证成功  2 验证失败

     */
    public function showLostCreditFromLocal(Request $request)
    {
        $newList = [];
        try{
            $request->validate([
                'uuid' => 'required',
                'token' => 'required',
            ]);
            // 发送一个crm查询
            $data = $request->all();
            $status = 1;
            $info = FuncLostCreditRecord::where([
                ['code', $data['uuid']],
                ['token', $data['token']]
            ])->first();
            if(!$info) {
                $status = 2;
            } else {
                $dataList = json_decode($info->data, true);
                $currentDate = date('Y-m-d');

                foreach ($dataList as &$item) {
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
                $info->token = $this->createToken();
                $info->save();
            }
        } catch (\Exception $e) {
            $status = 0;
        }


        return view($this->viewPrefix.'show', [
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
    public function showLostCredit(Request $request) {
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
                    $currentDate = date('Y-m-d');
                    foreach ($dataList as &$item) {
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
        } catch (\Exception $e) {
            $status = 0;
        }


        return view($this->viewPrefix.'show', [
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
     * 验证查询crm数据
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
        if($result && $result['code'] == 200) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 提交数据到crm
     * @param $list
     * @param $uuid
     */
    private function submitToCrm($list, $uuid)
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
        $param = [
            'type' => 'credit',
            'action' => 'submitRecord',
            'param' => [
                'uuid' => $uuid,
                'data' => $data
            ]
        ];
        $this->getCrmData($param);
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


    /**
     * 从crm中间件获取数据
     * @override
     * @param $data
     * @return mixed
     */
    public function getCrmData($data){
        $guzzle = new Client();
        $response = $guzzle->post('www.localhost.com:1234/index.php',[
            'form_params' => $data,
            'synchronous' => true,
            'timeout' => 0,
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        return $result;
    }

    /**
     * 生成一个token
     * @return string
     */
    private function createToken()
    {
        return md5(time().mt_rand(1,10000000));
    }

}