<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Helpers\Utils\Participles;
use App\Models\Admin\Admin\SysAdminGroup;
use App\Models\Admin\Base\CallCenter\SysTemplate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TemplateController extends BaseController
{
    private $view_prefix = 'admin.base.callCenter.template.';

    /**
     * 列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view($this->view_prefix.'index');
    }

    /**
     * api list
     * @param Request $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getList(Request $request){
        $content = $request->get('content');
        $keywords = (new Participles($content))->run();
        if($keywords) {
            $selectRow = "( ";
            $new_keywords = [];
            foreach ($keywords as $v) {
                $new_keywords[] = " keyword like '%$v%' ";
            }
            $str = implode("or", $new_keywords);
            $selectRow .= $str;
            $selectRow .= " ) ";
            $list = SysTemplate::whereRaw($selectRow)->select(['id','content'])->get();
            if(count($list)) return $this->ajax_return(200, 'success', $list);
            else return $this->ajax_return(500, '没有数据');
        } else {
            return $this->ajax_return(500, '没有数据');
        }
    }

    /**
     * 获取模板列表
     * @param Request $request
     * @return array
     * @todo 根据manager id 做分类  以及  点击数量排序 未做
     */
    public function getTemplateList(Request $request){
        $manager_id = $request->get('manager_id');
        $list = SysTemplate::get();
        if($list) return $this->ajax_return(200, 'success', $list->toArray());
        else return $this->ajax_return(500, '暂无数据');
    }

    /**
     * 新增界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function create(){
        $groups = SysAdminGroup::get()->toArray();
        return view($this->view_prefix.'add',  ['groups' => $groups]);
    }

    /**
     * 新增数据
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $data = $this->get_params($request, ['content','group_id','sort','keyword','answer']);
        SysTemplate::create($data);
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $groups = SysAdminGroup::get()->toArray();
        $template = SysTemplate::where('id', $id)->first();
        return view($this->view_prefix.'edit', ['groups' => $groups, 'template' => $template]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id){
        $data = $this->get_params($request, ['id','content','group_id','sort','answer']);
        SysTemplate::where('id', $data['id'])->update($data);
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 删除
     * @param Request $request
     * @param $ids
     * @return array
     */
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        SysTemplate::destroy($ids);
        return $this->ajax_return('200', '操作成功');
    }

    /**
     * 分页列表
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['keyword']);
        $condition = $this->getPagingList($selectInfo, ['keyword'=>'like']);
        $rows = $request->rows;
        $order = ($request->sort ?? 'id');
        $sort = $request->sortOrder ?? 'desc';
        $result = SysTemplate::where($condition)
            ->leftJoin('sys_admin_groups', 'sys_call_center_message_templates.group_id', '=', 'sys_admin_groups.id')
            ->select(['sys_call_center_message_templates.*', 'sys_admin_groups.group'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 暂时用不到
     */
    public function show(){}

    /**
     * 导出
     * @param Request $request
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['keyword']);
        $conditions = $this->getPagingList($param, ['keyword'=>'like']);
        if($request->has('id')){
            $data = SysTemplate::from('sys_call_center_message_templates as st')->where($conditions)
                ->leftJoin('sys_admin_groups as sg', 'st.group_id', '=', 'sg.id')
                ->select(['st.keyword','st.content', 'sg.group'])
                ->whereIn('st.id', explode(',',$request->get('id')))->get()->toArray();
        }else{
            $data = SysTemplate::from('sys_call_center_message_templates as st')
                ->where($conditions)
                ->leftJoin('sys_admin_groups as sg', 'st.group_id', '=', 'sg.id')
                ->select(['st.keyword','st.content', 'sg.group'])->get()->toArray();
        }
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        Excel::create('客服中心信息模板',function($excel) use ($cellData){
            $excel->sheet('信息模板', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

}
