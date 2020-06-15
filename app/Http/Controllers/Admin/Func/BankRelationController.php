<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\RpaBankRelation;
use App\Models\Admin\Rpa\RpaBankRelationTmp;
use Illuminate\Http\Request;
use Excel;

class BankRelationController extends BaseAdminController
{
    // 页面前缀
    protected $viewPrefix = "admin.func.bankRelation.";

    protected $name = "uf20银期关联";

    protected $relationStatusList = [
        '未关联',
        '关联成功',
        '关联失败'
    ];

    protected $accountStatusList = [
        '账户异常',
        '账户正常'
    ];

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $uid = $request->uid??'';
        $bankList = RpaBankRelation::groupBy('bank_name')->pluck('bank_name')->toArray();
        $this->log(__CLASS__, __FUNCTION__, $request, $this->name." 列表页");
        return view($this->viewPrefix.'index', compact('bankList', 'uid'));
    }

    /**
     * 列表
     * @param Request $request
     * @return
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['zjzh', 'relation_status', 'name', 'bank_name', 'uid', 'from_created_at', 'to_created_at']);
        $condition = $this->getPagingList($selectInfo, [
            'zjzh' => 'like',
//            'relation_status' => '=',
            'name' => 'like',
            'uid' => '=',
            'from_created_at' => '>=',
            'to_created_at' => '<=',
        ]);
        if('0' == $selectInfo['relation_status']) {
            $condition[] = [function($query) {
                $query->whereNull('relation_status')
                    ->orWhere('relation_status', '=', '0');
            }];
        } else {
            if($selectInfo['relation_status']) $condition[] = ['relation_status', '=', $selectInfo['relation_status']];
        }
        if(isset($selectInfo['bank_name']) && $selectInfo['bank_name']) {
            if(strpos(strtolower($selectInfo['bank_name']), 'three') > -1) {
                if('three' == $selectInfo['bank_name']) {
                    $cond = 'like';
                    $where = 'orWhere';
                } else {
                    $cond = 'not like';
                    $where = 'where';
                }
                $condition[] = [function($query) use ($cond, $where){
                    $query->$where('bank_name', $cond, "%建行%")
                        ->$where('bank_name', $cond, "%民生%")
                        ->$where('bank_name', $cond, "%兴业%");
                }];
            } else {
                $condition[] = ["bank_name", 'like', "%{$selectInfo['bank_name']}%"];
            }
        }
        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $sort = $request->sortOrder ?? 'desc';

        $list = RpaBankRelationTmp::from("rpa_bank_relation_tmps as tmp")
            ->leftJoin('rpa_customer_managers as rcm', 'rcm.id', 'tmp.mid')
            ->leftJoin('rpa_bank_relations as rbr', 'rbr.uid', 'tmp.id')
            ->where($condition)
            ->orderBy($order, $sort)
            ->select(['rbr.*', 'rcm.name', 'rcm.idCard', 'rcm.name', 'rcm.yybName','rcm.customerManagerName'])
            ->paginate($rows);
        foreach ($list as $k => &$v) {
            $v->relationStatusName = $this->relationStatusList[$v->relation_status]??'状态未知';
            $v->accountStatusName = isset($this->accountStatusList[$v->account_status])??'状态未知';
            $v->bank_number = substr_replace($v->bank_number, "********", 4, 8);
            $v->idCard = substr_replace($v->idCard, "********", 6, 8);
        }
        return $list;
    }

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request){
        $selectInfo = $this->get_params($request, ['zjzh', 'relation_status', 'name', 'bank_name', 'uid', 'from_created_at', 'to_created_at', 'id']);
        $condition = $this->getPagingList($selectInfo, [
            'zjzh' => 'like',
            'relation_status' => '=',
            'name' => 'like',
            'uid' => '=',
            'from_created_at' => '>=',
            'to_created_at' => '<=',
        ]);
        if(isset($selectInfo['bank_name']) && $selectInfo['bank_name']) {
            if(strpos(strtolower($selectInfo['bank_name']), 'three') > -1) {
                if('three' == $selectInfo['bank_name']) {
                    $cond = 'like';
                    $where = 'orWhere';
                } else {
                    $cond = 'not like';
                    $where = 'where';
                }
                $condition[] = [function($query) use ($cond, $where){
                    $query->$where('bank_name', $cond, "%建行%")
                        ->$where('bank_name', $cond, "%民生%")
                        ->$where('bank_name', $cond, "%兴业%");
                }];
            } else {
                $condition[] = ["bank_name", 'like', "%{$selectInfo['bank_name']}%"];
            }
        }
        $prefix = RpaBankRelation::from('rpa_bank_relations as rbr')
            ->leftJoin('rpa_customer_managers as rcm', 'rcm.id', 'rbr.uid')
            ->where($condition)
            ->where([['rbr.status', '!=', -1]])
            ->orderBy('created_at', 'desc');

        if(isset($selectInfo['id'])){
            $prefix = $prefix->whereIn('rcm.id', explode(',',$selectInfo['id']));
        }
        $list = $prefix->select(['rbr.*', 'rcm.name', 'rcm.idCard', 'rcm.name', 'rcm.yybName','rcm.customerManagerName'])
            ->get()->toArray();
        $title = ['资金账号', '客户姓名', '营业部', '客户经理', '证件编号', '银行卡号', '银行', '银行网点', '币种', '关联结果','创建时间', '修改时间', '关联反馈'];

        $cellData = [];
        array_push($cellData, $title);
        foreach ($list as $k => $v) {
            $relationStatusName = $this->relationStatusList[$v['relation_status']]??'状态未知';
            $item = [
                $v['zjzh'],$v['name'],$v['yybName'],$v['customerManagerName'],$v['idCard'],$v['bank_number'],$v['bank_name'],
                $v['bank_branch'],$v['currency'],$relationStatusName,$v['created_at'],$v['updated_at'],$v['feedback'],
            ];
            array_push($cellData, $item);
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 银期关联列表");
        Excel::create('银期关联列表',function($excel) use ($cellData){
            $excel->sheet('银期关联列表', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    /**
     * 重新发送任务
     * @param Request $request
     * @return array
     */
    public function reSendTask(Request $request)
    {
        $id = $request->id;
        $bankRelation = RpaBankRelation::find($id);
        if(!$bankRelation) return $this->ajax_return(500, '记录不存在');
        if($bankRelation->status != 1) return $this->ajax_return(500, '该记录状态异常或者已被删除');
        if($bankRelation->relation_status != 2) return $this->ajax_return(500, '该记录状态无法重新发送任务');
        $bankRelation->relation_status = 0;
        $bankRelation->save();
        $this->sendTask($id);
        $this->log(__CLASS__, __FUNCTION__, $request, $this->name." 重新关联");
        return $this->ajax_return(200, '操作成功');
    }

    /**
     * 查看页面 目前是查看图片
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $bankRelation = RpaBankRelation::find($id);
        $pathList = explode(',', $bankRelation->picture_path);
        $resultList = [];
        foreach($pathList as $v) {
            $resultList[] = encrypt($v);
        }
        return view($this->viewPrefix.'show', ['list' => $resultList]);
    }

    /**
     * 查看图片
     * 
     */
    public function showImg(Request $request)
    {
        $url = $request->url;
        $url = decrypt($url);
        $info = getimagesize($url);
        $mime = $info['mime'];
        header("Content-type:$mime");
        echo file_get_contents($url);
    }

    /**
     * 发送任务
     * @param $id
     */
    private function sendTask($id)
    {
        $jsonData = [
            'key' => $id
        ];
        rpa_immedtasks::create([
            'name' => 'ReleaseRelationTask',
            'jsondata' => json_encode($jsonData, JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 数据脚本
     */
    public function dataScript()
    {
        $bankRelationList = RpaBankRelation::get();
        foreach ($bankRelationList as $bankRelation) {
            $uid = $bankRelation->uid; // customer_manager表 pk
            $relation = RpaBankRelation::find($bankRelation->id);
            $customerManager = rpa_customer_manager::find($uid);
            $tmp = RpaBankRelationTmp::create([
                'mid' => $customerManager->id
            ]);
            $relation->uid = $tmp->id;
            $relation->save();
        }
    }

}