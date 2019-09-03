<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\Admin\Func\rpa_address_recognition; 
use App\Models\Admin\Api\RpaCustomerInfo;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Rpa\rpa_immedtasks;

/**
 * JJRVisController
 * @author hsu lay
 */
class RecognitionController extends BaseAdminController{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 身份证识别 页");
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/Recognition/index', ['list' => $result]);
    }

    /**
     * 审核页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id){
        $info = rpa_address_recognition::find($id);
        //获取身份证照片
        $img = RpaCustomerInfo::where('fundAccount',$info->zjzh)->first();
        $path = "D:/uploadFile/customerImg/";
        $sfz_zm = $this->base64EncodeImage($path.$img->sfz_zm);
        return view('admin.func.Recognition.edit',['info' => $info,'sfz_zm' => $sfz_zm]);
    }

    /**
     * 审核
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->get_params($request, ['address_final']);
        $data['check'] = Auth::user()->realName;
        $data['check_time'] = date("Y-m-d H:i:s");
        $data['state'] = 1;
        $res = rpa_address_recognition::where('id',$id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "审核 身份证识别");
        return $this->ajax_return(200, '操作成功1！');
    }

    //查询信息
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['zjzh','status','from_created_at','to_created_at']);

        $condition = $this->getPagingList($selectInfo, ['zjzh'=>'=','status'=>'=','from_created_at'=>'>=','to_created_at'=>'<=']);
        
        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $sort = $request->sortOrder ?? 'desc';
        return rpa_address_recognition::where($condition)->orderBy($order,$sort)->orderBy('id','desc')->paginate($rows);
    }

    /**
     * 复核页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function review(Request $request, $id){
        $info = rpa_address_recognition::find($id);
        //获取身份证照片
        $img = RpaCustomerInfo::where('fundAccount',$info->zjzh)->first();
        $path = "D:/uploadFile/customerImg/";
        $sfz_zm = $this->base64EncodeImage($path.$img->sfz_zm);
        return view('admin.func.Recognition.review',['info' => $info,'sfz_zm' => $sfz_zm]);
    }

    /**
     * 复核
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reviewdata(Request $request, $id)
    {
        $data = $this->get_params($request, [['state',-1]]);
        $data['review'] = Auth::user()->realName;
        $data['review_time'] = date("Y-m-d H:i:s");
        $res = rpa_address_recognition::where('id',$id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "复核 身份证识别");
        return $this->ajax_return(200, '操作成功1！');
    }

    /**
     * 上报
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function report(Request $request, $id)
    {
        $data = [
            'name' => 'IDRecognition',
            'jsondata' => $id
        ];
        rpa_immedtasks::create($data);
        $res = rpa_address_recognition::where('id',$id)->update(['state'=>3]);
        
        $this->log(__CLASS__, __FUNCTION__, $request, "上报 身份证识别");
        return $this->ajax_return(200, 'rpa任务发布成功！');
    }
}
