<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\Admin\Func\rpa_customer_jkzx; 

/**
 * JJRVisController
 * @author hsu lay
 */
class JKZXPwdController extends BaseAdminController{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 监控中心密码 页");
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/JKZXPwd/index', ['list' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.func.JKZXPwd.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->get_params($request, ['name',['type','IB'],'tel','account','pwd']);
        $data['inputtime'] = time();
        $res = rpa_customer_jkzx::create($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "添加 监控中心客户");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id){
        $info = rpa_customer_jkzx::find($id);
        return view('admin.func.JKZXPwd.edit',['info' => $info]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->get_params($request, ['name',['type','IB'],'tel','account','pwd']);
        $data['fzjg'] = $data['type'];
        $res = rpa_customer_jkzx::where('id',$id)->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "更新 监控中心客户");
        return $this->ajax_return(200, '操作成功1！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request){
        $ids = $request->id;
        $ids = explode(',', $ids);
        $result = rpa_customer_jkzx::destroy($ids);
        
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 监控中心密码");
        return $this->ajax_return(200, '操作成功！');
    }

    //查询信息
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['customer','type','status','from_inputtime','to_inputtime']);

        //将时间改为时间戳
        $selectInfo['from_inputtime'] = strtotime($selectInfo['from_inputtime']);
        $selectInfo['to_inputtime'] = strtotime($selectInfo['to_inputtime']);
        
        $condition = $this->getPagingList($selectInfo, ['type'=>'=','status'=>'=','from_inputtime'=>'>=','to_inputtime'=>'<=']);
        
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('account', 'like', "%".$customer."%"));
        }elseif(!empty( $customer )){
            array_push($condition,  array('name', 'like', "%".$customer."%"));
        }
        $rows = $request->rows;
        $order = $request->sort ?? 'inputtime';
        $sort = $request->sortOrder ?? 'desc';
        return rpa_customer_jkzx::where($condition)->orderBy($order,$sort)->orderBy('id','desc')->paginate($rows);
    }

    /**
     * 发送短信页面
     */
    public function send(Request $request, $id){
        $info = rpa_customer_jkzx::find($id);
        $info['content'] = $this->get_sms_content($info);
        return view('admin.func.JKZXPwd.send',['info' => $info]);
    }

    /**
     * 重新发送短信页面
     */
    public function resend(Request $request, $id){
        $info = rpa_customer_jkzx::find($id);
        $info['lastcontent'] = $info['content'];
        $info['content'] = $this->get_sms_content($info);
        return view('admin.func.JKZXPwd.send',['info' => $info,'re' => true]);
    }

    /**
     * 发送短信
     */
    public function senddata(Request $request){
        $data = $this->get_params($request, ['id','tel','content']);
        $res = $this->yx_sms($data['tel'],$data['content']);
        if($res['status'] == '0'){
            $update = [
                'tel' => $data['tel'],
                'content' => $data['content'],
                'status' => 1
            ];
            rpa_customer_jkzx::where('id',$data['id'])->update($update);
            return $this->ajax_return(200, $res['msg']);
        }else{
            return $this->ajax_return(500, $res['msg']);
        }
    }

    /**
     * 短信一键发送
     */
    public function yjsend(Request $request){
        $ids = $request->ids;
        $has_send = 0;
        $send_succ = 0;
        $send_err = 0;
        foreach(explode(",",$ids) as $id){
            $jkzx = rpa_customer_jkzx::find($id);
            if($jkzx->status == 1){
                //已经发送过了
                $has_send++;
            }else{
                $content = $this->get_sms_content($jkzx);
                $res = $this->yx_sms($jkzx['tel'],$content);
                if($res['status'] == '0'){
                    $update = [
                        'tel' => $jkzx['tel'],
                        'content' => $content,
                        'status' => 1
                    ];
                    rpa_customer_jkzx::where('id',$id)->update($update);
                    $send_succ++;
                }else{
                    $send_err++;
                }
            }
        }
        $msg = "本次发送短信".count(explode(",",$ids))."条,成功".$send_succ."条，失败".$send_err."条，已经发送过".$has_send."条";
        return $this->ajax_return(200, $msg);
    }

    /**
     * 获取短信模板
     */
    private function get_sms_content($jkzx){
        if($jkzx->type == 'IB'){
            $content = "尊敬的".trim($jkzx['name'])."，您在我公司申请的账户已经成功，您监控中心的账号是“0001”十“资金账号”，密码是".trim($jkzx['pwd'])."，请您及时登录监控中心官网及时修改并保管好密码，同时邀请您加入交流群（群号523632258），希望对您有所帮助，入群时需验证您的姓名和账号。";
        }else{
            $content = "尊敬的".trim($jkzx['name'])."，您在我司已开户成功，您的中国期货市场监控中心账号是“0001”十“资金账号”,密码是".trim($jkzx['pwd'])."，请及时登录监控中心官网https://investorservice.cfmmc.com/修改并保管好密码。您可用微信及时搜索并关注“华安期货”公众号，获取更多资讯。";
        }
        return $content;
    }
}
