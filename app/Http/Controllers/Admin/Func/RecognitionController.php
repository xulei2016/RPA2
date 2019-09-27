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
use Excel;

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
        $selectInfo = $this->get_params($request, ['zjzh','state','from_created_at','to_created_at']);

        $condition = $this->getPagingList($selectInfo, ['zjzh'=>'=','state'=>'=','from_created_at'=>'>=','to_created_at'=>'<=']);
        
        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $sort = $request->sortOrder ?? 'desc';
        $data = rpa_address_recognition::where($condition)->orderBy($order,$sort)->orderBy('id','desc')->paginate($rows);
        foreach($data as $k=>&$v){
            $v->next_id = null;
            if($v->state == 0){
                for($i=$k+1;$i<count($data);$i++){
                    if($data[$i]['state'] == 0){
                        $v->next_id = $data[$i]['id'];
                        break;
                    }
                }
            }
            if($v->state == 1){
                for($i=$k+1;$i<count($data);$i++){
                    if($data[$i]['state'] == 1){
                        $v->next_id = $data[$i]['id'];
                        break;
                    }
                }
            }
        }
        return $data;
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
        $this->log(__CLASS__, __FUNCTION__, $request, "复核 身份证识别");

        $data = $this->get_params($request, [['state',-1],['address_final']]);
        $data['review'] = Auth::user()->realName;
        
        //判断审核人和复核人不能是同一个人
        $rec = rpa_address_recognition::where('id',$id)->first();
        if($rec->check == Auth::user()->realName){
            return $this->ajax_return(500, '审核人和复核人不能是同一人！');
        }
        $data['review_time'] = date("Y-m-d H:i:s");
        $res = rpa_address_recognition::where('id',$id)->update($data);
        return $this->ajax_return(200, '操作成功！');
    }
   
    /**
     * export
     */
    public function export(Request $request){
        $selectInfo = $this->get_params($request, ['zjzh','state','from_created_at','to_created_at']);

        $condition = $this->getPagingList($selectInfo, ['zjzh'=>'=','state'=>'=','from_created_at'=>'>=','to_created_at'=>'<=']);

        //设置需要导出的列，以及对应的表头
        $exportList = [
            'zjzh' => '资金账号',
            'address' => '普通识别',
            'address_deep' => '深度识别',
            'check' => '审核人',
            'check_time' => '审核时间',
            'review' => '复核人',
            'review_time' => '复核时间',
            'created_at' => '开户时间',
        ];

        if(isset($selectInfo['id'])){
            $data = rpa_address_recognition::whereIn('id', explode(',',$selectInfo['id']))->select(array_keys($exportList))->get()->toArray();
        }else{
            $data = rpa_address_recognition::where($condition)->select(array_keys($exportList))->get()->toArray();
        }
        //设置表头
        $cellData[] = array_values($exportList);

        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 身份证地址识别");
        Excel::create('身份证地址识别',function($excel) use ($cellData){
            $excel->sheet('客户列表', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
