<?php

namespace App\Http\Controllers\Admin\Base;

use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\SysMessage;
use App\Models\Admin\Base\SysMessageObjects;
use App\Models\Admin\Base\SysMessageTypes;
use App\Models\Admin\Base\SysSmsLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class MessageController extends BaseAdminController
{
    //通知列表
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "通知 列表");
        return view('admin/Base/message/index');
    }
    //sendMessage
    public function sendMessage(Request $request){
        //公告类型列表
        $types = SysMessageTypes::all();
        //发送对象
        $object = SysMessageObjects::orderBy('id','desc')->get();
        $this->log(__CLASS__, __FUNCTION__, $request, "发布通告 页面");
        return view('admin/Base/message/send', ['types' => $types, 'object' => $object]);
    }
    //send
    public function send(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "发布 通知");
        $data = $request->all();
        //保存该信息
        if(isset($data['user'])){
            $data['user'] = implode(',', $data['user']);
        }
        $data['add_time'] = $this->getTime();
        $res = SysMessage::create($data);
        if($res){
            return $this->ajax_return('200', '操作成功！');
        }else{
            return $res;
        }
    }
    //view
    public function view(Request $request)
    {
        $id = $request->nid;
        $notification = DatabaseNotification::find($id);
        Auth::user()->decrement('notification_count');
        $notification->markAsRead();
        return view('admin/Base/message/show',['notification' => $notification]);

    }
    //pagination
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['title','from_add_time','to_add_time']);
        $condition = $this->getPagingList($selectInfo, ['title'=>'like','from_add_time'=>'>=','to_add_time'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $sort = $request->sortOrder ?? 'desc';
        $result = Auth::user()->notifications()
            ->where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 标记所有信息已读
     */
    public function readAllMessage(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->log(__CLASS__, __FUNCTION__, $request, "阅读全部通知");
        return $this->ajax_return(200, '操作成功');
    }

    /****************************历史消息****************************************************/
    public function history_list(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "历史通知 列表");
        return view('admin/Base/message/history_list');
    }
    //view
    public function history_view(Request $request)
    {
        $id = $request->id;
        $info = SysMessage::find($id);
        return view('admin/Base/message/history_show',['info' => $info]);

    }
    //pagination
    public function history_pagination(Request $request){
        $selectInfo = $this->get_params($request, ['title','from_add_time','to_add_time']);
        $condition = $this->getPagingList($selectInfo, ['title'=>'like','from_add_time'=>'>=','to_add_time'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $result = SysMessage::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }
    /*******************************短信记录************************************************/
    public function sms_list(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "短信发送 列表");
        return view('admin/Base/message/sms_list');
    }
    public function sms_pagination(Request $request){
        $selectInfo = $this->get_params($request, ['type','phone','from_created_at','to_created_at']);
        $condition = $this->getPagingList($selectInfo, ['type'=>'=','phone'=>'=','from_created_at'=>'>=','to_created_at'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $result = SysSmsLog::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }
}
