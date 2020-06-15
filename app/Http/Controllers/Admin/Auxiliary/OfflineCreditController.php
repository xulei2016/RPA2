<?php

namespace App\Http\Controllers\Admin\Auxiliary;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Func\FuncOfflineCreditRecords;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_ulinecredit_sxstate;
use Illuminate\Http\Request;

class OfflineCreditController extends BaseAdminController
{
    private $viewPrefix = "admin.Auxiliary.OfflineCredit.";

    const TASK_STATUS_WAIT = 0; // 查询中
    const TASK_STATUS_NO_LOST_CREDIT= 1; // 无失信
    const TASK_STATUS_LOST_CREDIT = 2; // 失信
    const TASK_STATUS_FAIL = 3; // 查询失败

    const TIME_OUT = 600; // 任务超时时间

    /**
     * 图片类型
     * @var array
     */
    private $imageTypeList = [
        'sf' => '证券业失信截图',
        'cfa' => '期货业失信截图',
        'xyzg' => '信用中国截图',
        'hs' => '恒生黑名单截图',
//        'zxgk' => '执行信息公开网截图',
//        'gsxt' => '国家企信网截图',
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
     * 分页数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['name', 'idCard', 'code', 'type', 'date', 'status']);
        $condition = $this->getPagingList($selectInfo, [
            'name' => '=',
            'idCard' => '=',
            'code' => '=',
            'type' => '=',
            'date' => '=',
            'status' => '=',
        ]);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $list = FuncOfflineCreditRecords::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        foreach ($list as &$v) {
            $v->idCard = substr_replace($v->idCard, "********", 6, 8);
        }
        return $list;
    }

    /**
     * 新增页面
     * @param Request $request
     */
    public function create(Request $request){
        $type = $request->type;
        return view($this->viewPrefix.'add', compact('type'));
    }

    /**
     * 提交查询
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        $currentDate = date('Y-m-d');
        $dataList = [];
        if(isset($request->pName)) //自然人
        {
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
            $personRecord = FuncOfflineCreditRecords::where($where)
                ->where([
                    ['date', $currentDate],
                    ['status', 2]
                ])->first();
            if($personRecord) {
                return $this->ajax_return(201, $personRecord->code);
            }
        }

        $where['date'] = $currentDate;
        $record = FuncOfflineCreditRecords::create($where);

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
            $queryResult = rpa_ulinecredit_sxstate::where($condition)
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
                rpa_ulinecredit_sxstate::create($condition);
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
     * 循环查询
     * @param Request $request
     * @return array
     */
    public function loopQuery(Request $request)
    {
        $code = $request->code;
        $record = FuncOfflineCreditRecords::where(['code' => $code])->first();
        if(!$record) {
            return $this->ajax_return(500, '', '查询异常,请重试');
        }
        $currentDate = date('Y-m-d');
        $dataList = json_decode($record->data, true);
        $resultList = [];
        foreach ($dataList as &$data) {
            $result = rpa_ulinecredit_sxstate::where([
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
            return $this->ajax_return(200, '无失信记录');
        }

    }



    /**
     * 查看图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id){
        $newList = [];
        $info = FuncOfflineCreditRecords::where([['id', $id],
        ])->first();
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
            $jjr = rpa_ulinecredit_sxstate::where([
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
        return view($this->viewPrefix.'show', [
            'info' => $info,
            'list' => $newList
        ]);
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
     * 判断任务结果
     * @param $data
     * @return int
     */
    private function judgeResult($data)
    {
        $data = $data->toArray();
        $checkList = ['sfstate', 'cfastate', 'xyzgstate', 'hsstate'];
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
            'name' => 'Supervision_Uline_temp',
            'jsondata' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s')
        ];
        rpa_immedtasks::create($add);
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
     * 下载图片
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function downloadImg(Request $request, $id){
        $record = FuncOfflineCreditRecords::find($id);
        $dataList = json_decode($record->data, true);
        $date = $record->date;
        //1.创建并打开压缩包
        $zip = new \ZipArchive();
        $name = $record->name."_".$record->idCard.".zip";
        $zip->open($name,\ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $paths = ['sfpaths', 'cfapaths', 'xyzgpaths', 'hspaths'];
        foreach ($dataList as $k => $v) {
            $zip->addEmptyDir($v['kind']);
            $detail = rpa_ulinecredit_sxstate::where([
                ['name', $v['name']],
                ['idCard', $v['idCard']],
                ['quedate', $date],
            ])->orderBy('id', 'desc')->first();
            foreach ($paths as $path) {
                $imgs = explode(',', $detail->$path);
                foreach ($imgs as $img) {
                    $basename = basename($img);
                    $zip->addFile($img, "/".$v['kind']."/".$basename);
                }

            }
        }
        //3.关闭压缩包
        $zip->close();
        //4.输出
        return response()->download($name)->deleteFileAfterSend(true);
    }
}