<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Func\rpa_capitalrefers;
use App\Models\Admin\Func\rpa_oabremindings;
use App\Models\Admin\Rpa\rpa_immedtasks;
use Illuminate\Http\Request;

/**
 * JJRVisController
 * @author hsu lay
 */
class OabremindingFuncController extends BaseAdminController{
    //RPA list
    public function oabIndex(Request $request){
        $varietyList = rpa_capitalrefers::all();
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 可用资金查询");
        return view('admin/func/Oabremind/index',['varietyList' => $varietyList]);
    }

    //RPA add
    public function oabAdd(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 可用资金查询");
        $varietyList = rpa_capitalrefers::all();
        return view('admin/func/Oabremind/add',['varietyList' => $varietyList]);
    }

    //RPA insert
    public function oabInsert(Request $request){
        $data = $this->get_params($request, ['khh','tid']);
        $data['created_at'] = $this->getTime();
        $this->log(__CLASS__, __FUNCTION__, $request, "插入 可用资金查询 信息");
        rpa_oabremindings::create($data);
        $this->oabImmedtask($data['khh']);
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * typeChange
     */
    public function oabTypeChange(Request $request){
        $id = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 可用资金查询 状态");
        rpa_oabremindings::where("id",$id)->update(['state'=>2]);
        return $this->ajax_return(200, '操作成功！');
    }

    //delete RPA
    public function oabDelete(Request $request){
        $ids = $request->id;
        $ids = explode(',', $ids);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 可用资金查询 客户");
        rpa_oabremindings::destroy($ids);
        return $this->ajax_return(200, '操作成功！');
    }

    //pagenation
    public function oabPagination(Request $request){
        $selectInfo = $this->get_params($request, ['customer','state','tid','from_created_at','to_created_at']);
        $condition = [];
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('khh', '=', $customer));
        }elseif(!empty( $customer )){
            array_push($condition,  array('name', '=', $customer));
        }
        if($selectInfo['from_created_at']){
            array_push($condition, ['created_at','>=',$selectInfo['from_created_at']]);
        }
        if($selectInfo['to_created_at']){
            array_push($condition, ['created_at','<=',$selectInfo['to_created_at']]);
        }
        if(null != $selectInfo['state'] && 'undefined' != $selectInfo['state']){
            array_push($condition, ['state','=',$selectInfo['state']]);
        }
        if(null != $selectInfo['tid'] && 'undefined' != $selectInfo['tid']){
            array_push($condition, ['tid','=',$selectInfo['tid']]);
        }
        $rows = $request->rows;
        $data = rpa_oabremindings::where($condition)->paginate($rows);
        foreach($data as $v){
            $capital = rpa_capitalrefers::find($v['tid']);
            $v['capital_name'] = $capital['name'];
            $v['capital_exfund'] = $capital['exfund'];
        }
        return $data;
    }

    //发布任务
    public function oabImmedtask($khh){
        $data = ['name'=>'OABReminding','jsondata'=>"{'khh':'$khh'}"];
        return rpa_immedtasks::create($data);
    }


    /////////////////////////////////////////////////////////品种操作/////////////////////////////////////////////////////


    //RPA list
    public function varietyList(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 品种");
        return view('admin/func/Oabremind/varietyList');
    }

    //RPA add
    public function varietyAdd(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 品种");
        return view('admin/func/Oabremind/varietyAdd');
    }

    //RPA edit
    public function varietyEdit(Request $request){
        $id = $request->id;
        $info = rpa_capitalrefers::find($id);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 品种");
        return view('admin/func/Oabremind/varietyEdit',['info'=>$info]);
    }

    //RPA insert
    public function varietyInsert(Request $request){
        $data = $this->get_params($request, ['desc','name','exfund']);
        $this->log(__CLASS__, __FUNCTION__, $request, "插入 品种");
        rpa_capitalrefers::create($data);
        return $this->ajax_return(200, '操作成功！');
    }

    //RPA update
    public function varietyUpdate(Request $request){
        $data = $this->get_params($request, ['id','desc','name','exfund'],false);
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 品种");
        rpa_capitalrefers::where("id",$data['id'])->update($data);
        return $this->ajax_return(200, '操作成功！');
    }

    //delete RPA
    public function varietyDelete(Request $request){
        $id = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 品种");
        rpa_capitalrefers::destroy($id);
        return $this->ajax_return(200, '操作成功！');
    }

    //pagenation
    public function varietyPagination(Request $request){
        $condition = [];
        $rows = $request->rows;
        return rpa_capitalrefers::where($condition)->paginate($rows);
    }
}
