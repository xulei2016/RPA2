<?php
namespace App\Http\Controllers\Admin\ZT;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Api\Tmp\TmpSeatApply;
use App\Models\Admin\Api\Tmp\TmpCustomer;

use Illuminate\Http\Request;
use App\Exceptions\zt\ZtException;
use Excel;
use Illuminate\Support\Facades\Auth;

/**
 * SeatApplyController
 * @author wang hui
 */
class SeatApplyController extends BaseAdminController
{
    protected $view_prefix = "admin.ZT.SeatApply.";
    /**
     * 次席申请列表页
     * @return: page
     */
    public function index()
    {
        return view($this->view_prefix.'index');
    }

    /**
     * 查询申请列表数据
     * @return: list
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['user', 'business_type', 'status']);
        $condition = $this->getPagingList($selectInfo, ['business_type'=>'=', 'status'=>'=']);

        //资金账号或用户名模糊查询
        $manager = $selectInfo['user'];
        if ($manager && is_numeric($manager)) {
            array_push($condition, array('tmp_customers.fundsNum', 'like', "%".$manager."%"));
        } elseif (!empty($manager)) {
            array_push($condition, array('tmp_customers.name', 'like', "%".$manager."%"));
        }

        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $order = 'tmp_seat_applies.'.$order;
        $sort = $request->sortOrder ?? 'desc';
        return TmpSeatApply::select('*', 'tmp_seat_applies.id as id', 'tmp_seat_applies.created_at as created_at')
        ->leftJoin('tmp_customers', 'tmp_seat_applies.user_id', 'tmp_customers.id')
        ->where($condition)
        ->orderBy($order, $sort)->paginate($rows);
    }

    /**
     * 初审页面
     * @return: page
     */
    public function show($id)
    {
        $data = TmpSeatApply::select('*', 'tmp_seat_applies.id as id')
        ->where('tmp_seat_applies.id', $id)
        ->leftJoin('tmp_customers', 'tmp_seat_applies.user_id', 'tmp_customers.id')
        ->first();
        //办理中
        if ($data->status == 1) {
            $reasons = $this->getFailReasons($data->business_type, $data->counter_type);
        //$reasons = ["有持仓", "已开通了该次席", "已开通了其他次席需要先取消再办理", "其他"];
        } else {
            $reasons = ["证件地址不同", "有效期不一致", "证件过期", "非本人身份证", "其他"];
        }
        
        return view($this->view_prefix.'accept', compact('data', 'reasons'));
    }

    /**
     * 获取办理失败原因
     * @param $businessType 业务类型 开通1/取消2
     * @param $counterType 柜台类型  CTP1/易盛2
     * @return:
     */
    private function getFailReasons($businessType, $counterType)
    {
        if ($businessType == 1) {
            $reasons = ["有持仓", "已开通该次席", "已开通其他次席需要先办理取消"];
        } else {
            //ctp
            if ($counterType == 1) {
                $reasons = ["有持仓", "未开通该次席"];
            //易盛
            } else {
                $reasons = ["未开通该次席"];
            }
        }

        $reasons[] = "其他";
        return $reasons;
    }

    /**
     * 设置次席申请的状态
     * @return:
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
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 次席申请办理状态");

        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * 更新次席申请状态和原因
     * @param string $id 记录id
     * @param string $status 状态
     * @param string $reason 办理失败原因
     * @param string $send_tpl 短信内容
     * @return:
     */
    public function updateStatus($id, $status, $reason='', $send_tpl)
    {   
        $obj = TmpSeatApply::where("id", $id)->first();

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

        TmpSeatApply::where("id", $id)->update($update);
        $user = TmpCustomer::where("id", $obj->user_id)->first();

        $send = true;
        if ($status == 0) {
            $typeName = $obj->business_type == 1 ? "开通" : "取消";
            $content = "尊敬的客户，您的次席".$typeName."业务已成功办理，请于1小时后尝试登录软件。如有疑问，请联系客服400-882-0628";
            $send = $this->sendSmsSingle($user->mobile, $content, 'CXSQ');
        } elseif ($status == 2) {
            $content = $send_tpl;
            $send = $this->sendSmsSingle($user->mobile, $content, 'CXSQ');
        }
        
        if (!$send) {
            throw new ZtException('业务办理成功，但是短信发送失败，请及时告知管理员');
        }
    
        return true;
    }

    
    /**
     * 获取storage图片
     * @return: 图片资源
     */
    public function getFile(Request $request)
    {
        $data = $this->get_params($request, ['key']);
        return response()->file(storage_path().'/'.$data['key']);
    }


    /**
     * 导出excel
     * @return: 表格
     */
    public function export(Request $request)
    {
        $selectInfo = $this->get_params($request, ['id', 'user', 'business_type', 'status']);
        $condition = $this->getPagingList($selectInfo, ['business_type'=>'=', 'status'=>'=']);
        if (isset($selectInfo['id'])) {
            $data = TmpSeatApply::select('*', 'tmp_seat_applies.created_at as created_at')->leftJoin('tmp_customers', 'tmp_seat_applies.user_id', 'tmp_customers.id')->whereIn('tmp_seat_applies.id', explode(',', $selectInfo['id']))->get()->toArray();
        } else {
            $data = TmpSeatApply::select('*', 'tmp_seat_applies.created_at as created_at')->where($condition)->leftJoin('tmp_customers', 'tmp_seat_applies.user_id', 'tmp_customers.id')->where($condition)->get()->toArray();
        }
        
        $cellData = [];
        $cellHead = ["姓名", "资金账户", "类型", "柜台", "时间"];
        $cellData = [$cellHead];
        foreach ($data as $k=>$info) {
            $type = $info['business_type'] == 1 ? '申请' : '取消';
            $desc = $info['counter_type'] == 1 ? 'CTP' : '易盛';
            if ($info['counter_type'] == 2) {
                if ($info['type'] == 1) {
                    $desc.='张江9.0';
                } else {
                    $desc.='郑州9.0';
                }
            }
            $d = [$info['name'], $info['fundsNum'], $type, $desc, $info['created_at']];
            $cellData[] = $d;
        }

        $this->log(__CLASS__, __FUNCTION__, $request, "导出次席申请记录");
        Excel::create('次席申请', function ($excel) use ($cellData) {
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
        $result = TmpSeatApply::destroy($request->id);
        
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 次席申请记录");
        return $this->ajax_return(200, '操作成功！');
    }
}
