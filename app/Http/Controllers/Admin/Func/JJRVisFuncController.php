<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\rpa_jjrvis;
use Illuminate\Http\Request;

/**
 * JJRVisController
 * @author hsu lay
 */
class JJRVisFuncController extends BaseAdminController{
    //查询页展示
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 居间人回访 页");
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/JJRVis/index', ['list' => $result]);
    }

    /**
     * typeChange
     */
    public function typeChange(Request $request){
        $id = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 居间人回访 状态");
        $result = rpa_jjrvis::where('id',$id)->update(['status'=>1,'updatetime' => $this->getTime()]);
        return $this->ajax_return(200, '操作成功！');
    }

    //查询信息
    public function JJRpagination(Request $request){
        $selectInfo = $this->get_params($request, ['revisit','dept','manager','customer','status','from_completed_date','to_completed_date']);

        $condition = $this->getPagingList($selectInfo, ['revisit'=>'=','from_completed_date'=>'>=','to_completed_date'=>'<=','status'=>'=']);
        //居间
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('number', '=', $customer));
        }elseif(!empty( $customer )){
            array_push($condition,  array('mediatorname', '=', $customer));
        }
        //经理
        $manager = $selectInfo['manager'];
        if($manager && is_numeric( $manager )){
            array_push($condition,  array('managerNo', '=', $manager));
        }elseif(!empty( $manager )){
            array_push($condition,  array('manager_name', '=', $manager));
        }
        //部门
        $dept = $selectInfo['dept'];
        if(!empty( $dept )){
            array_push($condition,  array('deptname', '=', $dept));
        }
        $rows = $request->rows;
        return rpa_jjrvis::where($condition)->paginate($rows);
    }
}
