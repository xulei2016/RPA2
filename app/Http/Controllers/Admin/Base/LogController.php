<?php

namespace App\Http\Controllers\admin\Base;

use App\models\admin\base\SysLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use Excel;

class LogController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看日志");
        return view('admin.base.logs.index');
    }

    /**
     * show a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $info = SysLog::where('id', $id)->first();
        return view('admin.base.logs.show', ['info' => $info]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\models\admin\base\SysLog  $sysLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $ids)
    {
        $result = SysLog::destroy($ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除日志");
        return $this->ajax_return('200', '操作成功！');
    }
    
    /**
     * show
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['account','role','type']);
        $conditions = $this->getPagingList($data, ['account'=>'like', 'role'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder;
        $result = SysLog::where($conditions)
                ->orderBy($order, $sort)
                ->paginate($rows);
        return $result;
    }

    /**
     * export
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['account', 'id']);
        $conditions = $this->getPagingList($param, ['account'=>'like']);

        if(isset($param['id'])){
            $data = SysLog::where($conditions)->whereIn('id', explode(',',$param['id']))->get()->toArray();
        }else{
            $data = SysLog::where($conditions)->get()->toArray();
        }
        
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出日志");
        Excel::create('管理员操作日志表',function($excel) use ($cellData){
            $excel->sheet('信息库', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
