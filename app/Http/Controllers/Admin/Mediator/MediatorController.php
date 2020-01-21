<?php

namespace App\Http\Controllers\Admin\Mediator;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediatorController extends BaseAdminController{

    //首页
    public function index(Request $request)
    {
        $dept = $this->getDept();
        return view('admin/mediator/index',['dept' => $dept]);
    }

    //分页
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['status','flow_status','dept_id','mediator','startTime','endTime']);

        $condition = $this->getPagingList($selectInfo, ['status'=>'=','dept_id'=>'=']);

        //居间人姓名或编号
        $mediator = $selectInfo['mediator'];
        if($mediator && is_numeric( $mediator )){
            array_push($condition, ['number','like',"%".$mediator."%"]);
        }elseif(!empty( $mediator )){
            array_push($condition, ['name','like',"%".$mediator."%"]);
        }

        //流程状态
        $ids = [];
        $where = [
            ['status',1],
            ['part_b_date','<>','']
        ];
        $flow_status = $selectInfo['flow_status'];
        if($flow_status){
            switch ($flow_status){
                case 1:
                    $where[] = ['is_check',0];
                    break;
                case 2:
                    $where[] = ['is_check',1];
                    $where[] = ['is_sure',0];
                    break;
                case 3:
                    $where[] = ['is_sure',1];
                    $where[] = ['is_handle',0];
                    break;
                case 4:
                    $where[] = ['is_handle',1];
                    break;
                default:
                    break;
            }
        }

        //申请时间
        $start = $selectInfo['startTime'];
        $end = $selectInfo['endTime'];
        if($start){
            $where[] = ['part_b_date','>=',$start];
        }
        if($end){
            $where[] = ['part_b_date','<',$end];
        }
        if($flow_status || $start || $end){
            $ids = FuncMediatorFlow::where($where)->pluck('uid')->toArray();
        }
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        if($ids){
            $info = FuncMediatorInfo::with('dept')->where($condition)->whereIn('id',$ids)->orderBy($order,$sort)->paginate($rows);
        }else{
            $info = FuncMediatorInfo::with('dept')->where($condition)->orderBy($order,$sort)->paginate($rows);
        }
        foreach($info as $k=>$v){
            $flow = FuncMediatorFlow::where('uid',$v->id)->orderBy('id','desc')->first();
            $info[$k]->flow = $flow;
        }
        return $info;
    }

    //详情
    public function info(Request $request)
    {
        $id = $request->id;
        $info = FuncMediatorInfo::with("dept")->where('id',$id)->first();
        return view('admin/mediator/info',['info' => $info]);
    }

    //履历页面
    public function history(Request $request)
    {
        $id = $request->id;
        return view('admin/mediator/history',['id' => $id]);
    }

    //历史记录分页
    public function history_list(Request $request)
    {
        $selectInfo = $this->get_params($request, ['uid']);
        $condition = $this->getPagingList($selectInfo, ['uid'=>'=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $res = FuncMediatorFlow::with('dept')->where($condition)->orderBy($order,$sort)->paginate($rows);
        return $res;
    }

    //流程详情
    public function flow_info(Request $request)
    {
        $id = $request->id;
        $info = FuncMediatorFlow::where('id',$id)->first();
        return view('admin/mediator/flow_info',['info' => $info]);
    }

    //审核页面
    public function check(Request $request)
    {
        $id = $request->id;
        $flow = FuncMediatorFlow::where('id',$id)->first();
        //可以打回的步骤
        $steps = FuncMediatorStep::where('can_back',1)->get();
        return view('admin/mediator/check',['id' => $id, 'steps' => $steps, 'flow' => $flow]);
    }

    //审核
    public function check_data(Request $request)
    {
        $id = $request->id;
        $flow = FuncMediatorFlow::where('id',$id)->first();
        if(isset($request->status) && $request->status == 1){
            //通过
            $update = [
                'number' => $request->number,
                'remark' => $request->remark,
                'is_check' => '1',
                'check_time' => date('Y-m-d H:i:s'),
                'check_person' => Auth::guard('admin')->user()->realName
            ];
            if($request->rate <= 70){
                //判断居间比例是否发生变化
                if($request->rate != $flow->rate){
                    //发送短信，待确认比例
                    $content = "您好！您在我公司申请的居间协议已通过初步审核！请您凭手机号再次登陆居间申请系统确认居间返佣比例。注：确认居间返佣比例后方可生成居间编号及居间协议，请收到此短信后务必及时登陆确认。如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
                    $this->yx_sms($flow->info->phone,$content);
                }else{
                    //同步数据到crm
                    $this->to_crm($flow);

                    $update['is_sure'] = 1;
                    $update['sure_time'] = date('Y-m-d H:i:s');
                }
            }else{
                //同步数据到crm
                $this->to_crm($flow);

                $update['is_sure'] = 1;
                $update['sure_time'] = date('Y-m-d H:i:s');
            }
            FuncMediatorFlow::where('id',$id)->update($update);
        }else{
            //打回
            $update = [
                'is_back' => 1,
                'back_list' => implode(',',$request->back),
                'back_time' => date('Y-m-d H:i:s'),
                'back_person' => Auth::guard('admin')->user()->realName
            ];
            FuncMediatorFlow::where('id',$id)->update($update);
            //发短信
            $content = "温馨提示：由于您的如下信息没有按照要求提交（".trim($request->reason)."），请及时重新登录网签居间系统进行修改。详情请咨询400-8820-628";
            $this->yx_sms($flow->info->phone,$content);
        }
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * 同步居间到crm
     * @param $data
     * @return mixed
     */
    private function to_crm($data)
    {
        $data = $data->toArray();
        $post_data = [
            'type' => 'jjr',
            'action' => 'relationMediator',
            'param' => [
                'info' => $data
            ]
        ];
        $result = $this->getCrmData($post_data);
        return $result;
    }


}
