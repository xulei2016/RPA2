<?php


namespace App\Http\Controllers\Admin\Base\Flow;


use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowGroup;
use App\Models\Admin\Base\Flow\SysFlowInstance;
use App\Models\Admin\Base\Flow\SysFlowInstanceData;
use App\Models\Admin\Base\Flow\SysFlowInstanceRecords;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowNode;
use App\Models\Admin\Base\Flow\SysFlowTemplateForm;
use App\Services\Flow\Flow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FlowMineController extends BaseAdminController
{
    public $view_prefix = "Admin.Base.Flow.Mine.";

    const RECORD_STATUS_END = 9; // 已结束
    const RECORD_STATUS_UNTREATED = 0; // 未处理

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "流程 首页");
        return view($this->view_prefix.'index');
    }

    /**
     * 分页数据
     * @param Request $request
     * @return
     * @todo status 之后会进行改动
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['name', 'group_id', 'flow_id', 'type']);
        $adminId = auth()->guard()->user()->id;
        $instanceIds = SysFlowInstanceRecords::where('user_id', $adminId)->get(['instance_id']);
        $instanceArr = [];
        foreach ($instanceIds as $v) {
            $instanceArr[] = $v->instance_id;
        }
        $conditions = [];
        if(isset($data['name']) && $data['name']) {
            $conditions[] = [
                'sfi.title', 'like', '%'.$data['name'].'%'
            ];
        }
        if(isset($data['group_id']) && $data['group_id']) {
            $conditions[] = [
                'sfg.id', '=', $data['group_id']
            ];
        }
        if(isset($data['flow_id']) && $data['flow_id']) {
            $conditions[] = [
                'sfi.flow_id', '=', $data['flow_id']
            ];
        }

        if(isset($data['type']) && $data['type']) {
            if($data['type'] == 'todo') {
                $conditions[] = [
                    'sfi.status', '=', 0
                ];
            } elseif($data['type'] == 'complete') {
                $conditions[] = [
                    'sfi.status', '=', 9
                ];
            }
        }

        $result = SysFlow::from('sys_flow_instances as sfi')
            ->leftJoin('sys_flows as sf', 'sfi.flow_id', 'sf.id')
            ->leftJoin('sys_flow_groups as sfg', 'sf.groupId', 'sfg.id')
            ->where($conditions)
            ->whereIn('sfi.id', $instanceArr)
            ->orderBy('sfi.status', 'asc')
            ->orderBy('created_at', 'desc')
            ->select(['sfi.*','sfi.title as instanceName','sf.title as flowName','sfg.name as groupName'])
            ->paginate($rows);
        foreach ($result as &$v) {
            if($v->status == 9) {
                $v->statusName = '已完成';
                $v->nodeName = '已完成';
            } else {
                $record = SysFlowInstanceRecords::where('instance_id', $v->id)->orderBy('id', 'desc')->first();
                $node = SysFlowNode::where('id', $record->node_id)->first();
                $v->statusName = $this->getStatusName($v->status);
                $v->nodeName = $node->node_title;
            }
        }
        return $result;
    }

    /**
     * 首页未处理流程列表
     * @param Request $request
     * @return array
     */
    public function todoList(Request $request) {
        $adminId = auth()->guard('admin')->user()->id;
        $conditions = [
            ['sr.user_id', '=', $adminId],
            ['sr.status', '=', self::RECORD_STATUS_UNTREATED],
            ['si.status', '!=', 9],
        ];
        $list = SysFlowInstanceRecords::from('sys_flow_instance_records as sr')
            ->leftJoin('sys_flow_instances as si', 'si.id', 'sr.instance_id')
            ->leftJoin('sys_flow_nodes as sn', 'sn.id', 'sr.node_id')
            ->leftJoin('sys_flows as sf', 'sf.id', 'sr.flow_id')
            ->where($conditions)
            ->whereRaw('si.node_id = sr.node_id')
            ->orderBy('si.status', 'asc')
            ->orderBy('sr.created_at', 'desc')
            ->select(['si.id','si.title','si.created_at','sn.node_title','sf.title as flow_title'])
            ->get()
            ->toArray();
        return $this->ajax_return(200, 'success', $list);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id) {
        $userId = auth()->guard()->user()->id;
        $info = SysFlow::from('sys_flow_instances as sfi')
            ->leftJoin('sys_flows as sf', 'sfi.flow_id', 'sf.id')
            ->leftJoin('sys_flow_groups as sfg', 'sf.groupId', 'sfg.id')
            ->where('sfi.id', $id)
            ->select(['sfi.*','sfi.title as instanceName','sf.title as flowName','sf.template_id as template_id','sfg.name as groupName'])
            ->first();
        $info->nodeName = SysFlowNode::where('id', $info->node_id)->first()->node_title;
        $templates = SysFlowTemplateForm::where('template_id', $info['template_id'])->get();
        $fieldNameList = [];
        $fieldTypeList = [];
        foreach ($templates as $v) {
            $fieldNameList[$v->field] = $v->field_name;
            $fieldTypeList[$v->field] = $v->field_type;
        }
        $formList = SysFlowInstanceData::where('instance_id', $id)->get();
        foreach ($formList as &$v) {
            $v->field_showname = $fieldNameList[$v->field_name];
            $fieldType = $fieldTypeList[$v->field_name];
            if($fieldType == 'image') {
                $v->field_value = image2base64($v->field_value);
            } elseif($fieldType == 'file') {
                $v->field_value = customEncode($v->field_value);
            }
            $v->field_type = $fieldType;
        }
        $instance = SysFlowInstance::where('id', $id)->first();
        $records = SysFlowInstanceRecords::where([
            ['instance_id', '=' , $id],
            ['status', '!=', self::RECORD_STATUS_UNTREATED]
        ])->get();
        $flowService = new Flow();
        foreach ($records as &$v) {
            $nextNodeId = SysFlowLink::getNextNodes($v->flow_id, $v->node_id);
            $nextAdminNames = [];
            if($nextNodeId) {
                $nextAdminIds = $flowService->getNodeAuditorIds($instance, $nextNodeId);
                if($nextAdminIds) {
                    foreach ($nextAdminIds as $adminId){
                        $admin = SysAdmin::where('id', $adminId)->first();
                        if($admin) {
                            $nextAdminNames[] = $admin->realName;
                        }
                    }
                }
            }
            $admin = SysAdmin::where('id', $v->user_id)->first();
            $v->headImg = $admin?$admin->head_img:'';
            $v->statusName = $this->getStatusName($v->status);
            $v->nextAdminNames = empty($nextAdminNames)?'无':implode(',', $nextAdminNames);
            $v->nodeName = SysFlowNode::where('id', $v->node_id)->first()->node_title;
        }
        $record = SysFlowInstanceRecords::where([
            ['user_id', '=', $userId],
            ['status', '=', self::RECORD_STATUS_UNTREATED],
            ['instance_id', '=', $id],
            ['node_id', '=', $info->node_id]
        ])->first();
        return view($this->view_prefix.'show', [
            'info' => $info,
            'formList' => $formList,
            'recordList' => $records,
            'record' => $record
        ]);
    }

    /**
     * 获取柱状结构
     * @param Request $request
     * @return array
     */
    public function getMenuTree(Request $request) {
        $groups = SysFlowGroup::get();
        $list = [];
        foreach ($groups as $group) {
            $list[] = [
                'id' => $group->id,
                'name' => $group->name,
                'type' => 'group',
                'pid' => null,
                'open' => true
            ];
        }
        $flows = SysFlow::where('is_publish', 1)->get();
        foreach ($flows as $flow) {
            $list[] = [
                'id' => 'flow_'.$flow->id,
                'name' => $flow->title,
                'type' => 'flow',
                'pid' => $flow->groupID
            ];
        }
        return $this->ajax_return(200, '', $list);
    }

    /**
     * 获取name值
     * @param $status
     * @return string
     */
    public function getStatusName($status) {
        switch ($status) {
            case '-1':
                $statusName = "打回";break;
            case 0:
                $statusName = '未处理';break;
            case self::RECORD_STATUS_END:
                $statusName = '同意';break;
            default:
                $statusName = '暂无';
        }
        return $statusName;
    }


    /**
     * 流程图
     * @param Request $request
     * @param $id
     * @return array
     */
    public function design(Request $request, $id){
        $result = SysFlowInstance::where('id', $id)->first();
        return view($this->view_prefix.'design', ['info' => $result]);
    }

    /**
     * @param Request $request
     */
    public function downloadFile(Request $request){
        $url = $request->url;
        return Storage::disk('local')->download(customDecode($url));
    }

}