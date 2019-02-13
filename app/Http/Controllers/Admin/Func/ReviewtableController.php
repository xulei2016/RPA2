<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\rpa_jjrvis;
use App\Models\Admin\Func\rpa_reviewtables;
use Illuminate\Http\Request;

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

    /**
     * typeChange
     */
    public function typeChange(Request $request){
        $id = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 开户云回访 状态");
        $result = rpa_reviewtables::where('id',$id)->update(['status'=>1]);
        return $this->ajax_return(200, '操作成功！');
    }

    //查询信息
    public function JJRpagination(Request $request){
        $selectInfo = $this->get_params($request, ['reviewPeopName','videoPeopName','checkPeopName','customer','status','from_completed_date','to_completed_date']);

        $condition = $this->getPagingList($selectInfo, ['reviewPeopName'=>'=','videoPeopName'=>'=','checkPeopName'=>'=','from_completed_date'=>'>=','to_completed_date'=>'<=','status'=>'=']);
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('capital', '=', $customer));
        }elseif(!empty( $customer )){
            array_push($condition,  array('customername', '=', $customer));
        }
        $rows = $request->rows;
        return rpa_reviewtables::where($condition)->paginate($rows);
    }
}
