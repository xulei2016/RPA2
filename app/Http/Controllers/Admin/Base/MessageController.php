<?php

namespace App\Http\Controllers\Admin\Base;

use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Base\SysMessage;
use App\Models\Admin\Base\SysMessageTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $this->log(__CLASS__, __FUNCTION__, $request, "发布通告 页面");
        return view('admin/Base/message/send', ['types' => $types]);
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
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $result = Auth::user()->notifications()->where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
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
}
