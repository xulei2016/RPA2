<?php
namespace App\Http\Controllers\Admin\ZT;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Api\Tmp\TmpYingqiChange;
use App\Models\Admin\Api\Tmp\TmpCustomer;

use Illuminate\Http\Request;
use App\Exceptions\zt\ZtException;
use Illuminate\Support\Facades\Auth;

use Excel;

/**
 * BalanceAccountController
 * @author wang hui
 */
class BalanceAccountController extends BaseAdminController
{
    protected $view_prefix = "admin.ZT.BalanceAccount.";
    protected $autoRelationBanks = ["建设银行", "兴业银行", "民生银行"];
    /**
     * 结算账户新增、变更列表页
     * @return: page
     */
    public function index(Request $request)
    {
        return view($this->view_prefix.'index');
    }

    /**
     * 查询申请列表数据
     * @return: list
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['user', 'type', 'status', 'order_num']);
        $condition = $this->getPagingList($selectInfo, ['type'=>'=', 'status'=>'=', 'order_num'=>'=']);

        //资金账号或用户名模糊查询
        $manager = $selectInfo['user'];
        if ($manager && is_numeric($manager)) {
            array_push($condition, array('tmp_customers.fundsNum', 'like', "%".$manager."%"));
        } elseif (!empty($manager)) {
            array_push($condition, array('tmp_customers.name', 'like', "%".$manager."%"));
        }

        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $order = 'tmp_yingqi_changes.'.$order;
        $sort = $request->sortOrder ?? 'desc';
        return TmpYingqiChange::select('*', 'tmp_yingqi_changes.id as id', 'tmp_yingqi_changes.created_at as created_at')
        ->where($condition)
        ->leftJoin('tmp_customers', 'tmp_yingqi_changes.user_id', 'tmp_customers.id')
        ->orderBy($order, $sort)->paginate($rows);
    }

    /**
     * 初审页面
     * @return:
     */
    public function show($id)
    {
        $data = TmpYingqiChange::select('*', 'tmp_yingqi_changes.id as id')
        ->where('tmp_yingqi_changes.id', $id)
        ->leftJoin('tmp_customers', 'tmp_yingqi_changes.user_id', 'tmp_customers.id')
        ->first();
        //办理中
        if ($data->status == 1) {
            $reasons = ["存在同行结算账户", "网点名称填写错误", "银期关联出错", "老卡银期关系未撤销", "其他"];
        } else {
            //初审
            $reasons = ["证件地址不同", "有效期不一致", "证件过期", "非本人身份证", "卡号错误", "银行名称错误", "其他"];
        }
        
        return view($this->view_prefix.'accept', compact('data', 'reasons'));
    }


    /**
     * 设置结算账户新增、变更状态
     */
    public function setStatus(Request $request, $id)
    {
        $data = $this->get_params($request, [['type', 'accept'],['status', -1],['reason', []], ['send_tpl', '']]);
        if ($data['status'] == -1 && (!count($data['reason']) || empty($data['send_tpl']))) {
            return $this->ajax_return(500, '请填写原因');
        }

        if ($data['type'] == 'success') {
            //办理成功 0：成功  2：办理失败
            $status = $data['status'] == 1 ? 0 : 2;
        } else {
            //接受办理  1：办理中  2：办理失败
            $status = $data['status'] == 1 ? 1 : 2;
        }
        $reason = '';
        if (count($data['reason'])) {
            $reason = implode(",", $data['reason']);
        }

        $this->updateStatus($id, $status, $reason, $data['send_tpl']);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 结算账户新增、变更状态");

        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * 更新结算账户新增、变更 状态和原因
     * @param string $id 记录id
     * @param string $status 状态
     * @param string $reason 办理失败原因
     * @param string $send_tpl 短信内容
     * @return:
     */
    public function updateStatus($id, $status, $reason='', $send_tpl)
    {
        $obj = TmpYingqiChange::where("id", $id)->first();

        //办理中更新办理人 否则更新审核人
        $nameField = $obj->status == 1 ? 'deal_user' : 'audit_user';
        $timeField = $obj->status == 1 ? 'deal_time' : 'audit_time';
        $name = Auth::guard('admin')->user()->realName;
        $update = [
            'status' => $status, 
            'reason' => $reason, 
            $nameField => $name,
            $timeField => date('Y-m-d H:i:s')
        ];

        TmpYingqiChange::where("id", $id)->update($update);
        $user = TmpCustomer::where("id", $obj->user_id)->first();

        $send = true;
        if ($status == 0) {
            $typeName = $obj->type == 1 ? "新增" : "变更";
            $content = "尊敬的客户，您的结算账户".$typeName."业务已成功办理。";
            if (!in_array($obj->opening_bank, $this->autoRelationBanks)) {
                $content.="请自行关联银期。";
            }
            $content.="如有疑问，请联系客服400-882-0628";
            $send = $this->sendSmsSingle($user->mobile, $content, 'CXSQ');
        } elseif ($status == 2) {
            $content = $send_tpl;
            $send = $this->sendSmsSingle($user->mobile, $content, 'CXSQ');
        }
        if (!$send) {
            throw new ZtException('业务办理成功，但是短信发送失败');
        }

        return true;
    }


    /**
     * 导出excel
     * @return: 表格
     */
    public function export(Request $request)
    {
        $selectInfo = $this->get_params($request, ['id', 'user', 'type', 'status', 'order_num']);
        $condition = $this->getPagingList($selectInfo, ['type'=>'=', 'status'=>'=', 'order_num'=>'=']);

        if (isset($selectInfo['id'])) {
            $data = TmpYingqiChange::select('*', 'tmp_yingqi_changes.created_at as created_at')->leftJoin('tmp_customers', 'tmp_yingqi_changes.user_id', 'tmp_customers.id')->whereIn('tmp_yingqi_changes.id', explode(',', $selectInfo['id']))->get()->toArray();
        } else {
            $data = TmpYingqiChange::select('*', 'tmp_yingqi_changes.created_at as created_at')->where($condition)->leftJoin('tmp_customers', 'tmp_yingqi_changes.user_id', 'tmp_customers.id')->where($condition)->get()->toArray();
        }
        
        $cellData = [];
        $cellHead = ["姓名", "资金账户", "类型", "原卡号", "新卡号", "网点", "时间"];
        $cellData = [$cellHead];
        foreach ($data as $k=>$info) {
            $type = $info['type'] == 1 ? '新增' : '变更';
            $old = $info['old_account'];
            if ($info['old_bank_name']) {
                $old.='('.$info['old_bank_name'].')';
            }
            $new = $info['bank_card_num'].'('.$info['opening_bank'].')';
            $d = [$info['name'], $info['fundsNum'], $type, $old, $new, $info['bank_name'], $info['created_at']];
            $cellData[] = $d;
        }

        $this->log(__CLASS__, __FUNCTION__, $request, "导出 结算账户变更记录");
        Excel::create('结算账户新增、变更', function ($excel) use ($cellData) {
            $excel->sheet('信息库', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }


    /**
     * 选中删除
     * @return:
     */
    public function destroy(Request $request)
    {
        $result = TmpYingqiChange::destroy($request->id);
        
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 结算账户变更 记录");
        return $this->ajax_return(200, '操作成功！');
    }
}
