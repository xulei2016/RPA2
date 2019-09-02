<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\rpa_jjrvis;
use App\Models\Admin\Func\rpa_reviewtables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * JJRVisController
 * @author hsu lay
 */
class ReviewtableController extends BaseAdminController{
    //查询页展示
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 开户云回访 页");
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/Reviewtables/index', ['list' => $result]);
    }

    public function report(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 开户云回访报表 页");
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/Reviewtables/report', ['list' => $result]);
    }

    public function reportpagination(Request $request)
    {
        //获取数据
        $selectInfo = $this->get_params($request, ['reviewPeopName','from_openingTime','to_openingTime']);
        $condition = $this->getPagingList($selectInfo, ['reviewPeopName'=>'=','from_openingTime'=>'>=','to_openingTime'=>'<=']);
        $rows = $request->rows;

        $select = [
            DB::raw('reviewPeopName,count(if(ischeck=1,true,null)) as must'),
            DB::raw('reviewPeopName,count(if(status=1,true,null)) as success'),
            DB::raw('reviewPeopName,count(if(status=-1,true,null)) as error')
        ];

        return rpa_reviewtables::select($select)->where($condition)->groupBy("reviewPeopName")->paginate($rows);

    }
    /**
     * edit
     */
    public function edit(Request $request){
        $id = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 开户云回访状态 页");
        return view('admin/func/reviewtables/edit',['id' => $id]);
    }

    public function update(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 开户云回访状态");
        $id = $request->id;
        $data = $this->get_params($request, [['status',-1],['khyj',''],['bz',''],['reason', '']]);
        if($data['status'] == -1 && empty($data['reason'])){
            return $this->ajax_return(500, '回访失败原因不能为空！');
        }
        rpa_reviewtables::where("id",$id)->update($data);
        return $this->ajax_return(200, '操作成功！');
    }

    //查询信息
    public function JJRpagination(Request $request){
        $selectInfo = $this->get_params($request, ['reviewPeopName','videoPeopName','checkPeopName','customer','status','from_openingTime','to_openingTime']);

        $condition = $this->getPagingList($selectInfo, ['reviewPeopName'=>'=','videoPeopName'=>'=','checkPeopName'=>'=','from_openingTime'=>'>=','to_openingTime'=>'<=','status'=>'=']);
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('capital', '=', $customer));
        }elseif(!empty( $customer )){
            array_push($condition,  array('customername', '=', $customer));
        }
        $rows = $request->rows;
        $order = $request->sort ?? 'ischeck';
        $sort = $request->sortOrder ?? 'desc';
        return rpa_reviewtables::where($condition)->orderBy($order,$sort)->orderBy('id','desc')->paginate($rows);
    }
}
