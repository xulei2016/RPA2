<?php


/**
 * 流程测试
 */
namespace App\Http\Controllers\Admin\Base\Flow;

use DB;
use Flow;
use Illuminate\Http\Request;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowGroup;
use App\Models\Admin\Base\Flow\SysFlowTemplate;
use App\Models\Admin\Base\Flow\SysFlowInstance;
use App\Models\Admin\Base\Flow\SysFlowInstanceData as InstanceData;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * FlowController class
 *
 * @Description 流程中心
 * @author Hsu Lay
 * @since 20191126
 */
class FlowTextController extends BaseAdminController
{

    protected $flow = 'Admin.Base.Flow.';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stores(Request $request)
    {
        // $data=$request->all();

        //构造请求数据
        $data = [
            'flow_id' => 1,
            'title' => '掌厅/流程/证件有效期变更流程-梁杰-2019-12-5',
            'tpl' => [
                'address' => 'storage/zt/flow/zjbglc/123456.png',
                'name' => '梁杰',
                'IDCard' => '342422199101212111',
                'SFZ_ZM' => 'storage/zt/flow/zjbglc/SFZ_ZM.png',
                'SFZ_FM' => 'storage/zt/flow/zjbglc/SFZ_FM.png',
                'sign' => 'storage/zt/flow/zjbglc/sign.png'
            ],
        ];

        try{

            DB::beginTransaction();

            $flow=SysFlow::where('is_publish',1)->findOrFail($data['flow_id']);

            //当前实例流程数
            $work_num_today = SysFlowInstance::where('created_at', '>=', date('Y-m-d'))->count();
            $work_num_today += 1;
            
            $flowlink=SysFlowLink::where(['flow_id'=>$data['flow_id'],'type'=>'Condition'])->whereHas('node',function($query){
                $query->where('position',0);
            })->orderBy("sort","ASC")->first();

            $instance=SysFlowInstance::create([
                'title' => $data['title'],
                'flow_id' => $data['flow_id'],
                'work_num' => $flow->flow_no."-".date('Ymd')."00".$work_num_today,
                'user_id' => auth()->guard('admin')->id(),
                'circle' => 1,
                'status' => 0
            ]);
            
            //进程初始化
            //第一步看是否指定审核人
            
            Flow::setFirstNodeAuditor($instance,$flowlink);

            //流程表单数据插入 TODO
            if(isset($data['tpl'])){
                $res=[];
                foreach($data['tpl'] as $k=>$v){
                    if($files=$request->file('tpl')){
                        if(isset($files[$k])){
                            $destinationPath = './uploads/flow/' . date('Y-m') . '/';
                            $filename = uniqid() . '.' . $files[$k]->extension();
                            $files[$k]->move($destinationPath, $filename);
                            $v=substr($destinationPath, 1).$filename;
                        }
                    }
                    $res[]=[
                        'instance_id'=>$instance->id,
                        'flow_id'=>$instance->flow_id,
                        'field_name'=>$k,
                        'field_value'=>is_array($v)?implode('|', $v):$v
                    ];
                }
                InstanceData::insert($res);
            }
            $instance->save();
            DB::commit();

            $this->log(__CLASS__, __FUNCTION__, $request, "发起 流程");
            return $this->ajax_return('200', '操作成功！');

        }catch(\Exception $e){

            DB::rollback();
            return $this->ajax_return('500', '操作失败！');

        }
    }

    /**
     * resend a flow which is canceled
     *
     * @param Request $request 重新发起流程
     * @return array
     */
    public function resend(Request $request)
    {
        $instance_id=$request->input('instance_id',0);
        $instance_id = 184;

        try{
            DB::beginTransaction();
            $instance=SysFlowInstance::where(['status'=>-1])->findOrFail($instance_id);

            $flow=SysFlow::where('is_publish',1)->findOrFail($instance->flow_id);

            $flowlink=SysFlowLink::where(['flow_id'=>$instance->flow_id,'type'=>'Condition'])->whereHas('node',function($query){
                $query->where('position',0);
            })->orderBy("sort","ASC")->first();

            $instance->circle=$instance->circle+1;
            $instance->child_node_id=0;
            $instance->status=0;

            $instance->save();

            //进程初始化
            Flow::setFirstNodeAuditor($instance,$flowlink);
            
            DB::commit();
            $this->log(__CLASS__, __FUNCTION__, $request, "重新发起 流程");
            return $this->ajax_return('200', '操作成功！');
        }catch(\Exception $e){
            DB::rollback();
            return $this->ajax_return('500', '操作失败！');
        }
    }

    /**
     * pass once flow
     *
     * @param Request $request
     * @param [type] $id
     * @return array
     */
    public function pass(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            Flow::pass($id);
            
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return $this->ajax_return('500', '操作失败！', $e);
        }
        
        $this->log(__CLASS__, __FUNCTION__, $request, "流程 审批通过");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * unpass once flow
     *
     * @param Request $request
     * @param [type] $id
     * @return array
     */
    public function unpass(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            Flow::unpass($id);

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            return $this->ajax_return('500', '操作失败！', $e);
        }
        
        $this->log(__CLASS__, __FUNCTION__, $request, "流程 审批不通过");
        return $this->ajax_return('200', '操作成功！');
    }
}
