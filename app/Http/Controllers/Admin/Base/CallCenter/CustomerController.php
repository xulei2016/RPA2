<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Events\CallCenterCustomerChangeEvent;
use App\Events\CallCenterCustomerEvent;
use App\Models\Admin\Base\CallCenter\SysCustomer;
use App\Models\Admin\Base\CallCenter\SysRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CustomerController extends BaseController
{
    private $view_prefix = 'admin.base.callCenter.customer.';

    /**
     * 登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(){
        return view($this->view_prefix.'login');
    }

    /**
     * 忘记密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forget(){
        return view($this->view_prefix.'forget');
    }

    /**
     * 聊天
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chat(){
        return view($this->view_prefix.'chat');
    }

    /**
     * 用户登出
     * @param Request $request
     * @return array
     */
    public function logout(Request $request)
    {
        $this->customer_id = $request->post('customer_id');
        $this->noticeOnlineManager('customer_remove');
        $this->leaveOnlineVisitors($this->customer_id);
        return $this->ajax_return(200, 'success');
    }

    /**
     * 登录
     * @param Request $request
     * @return array
     */
    public function doLogin(Request $request){
        $name = $request->post('name', null);
        $this->avatar = $request->post('avatar', null);
        if(!$name) return $this->ajax_return(500, "缺少必要的参数姓名");
        $capital_account = $request->post('capital_account', null);
        $id_card = $request->post('id_card', null);
        $condition = [
            'name' => $name
        ];
        if($id_card) $condition['id_card'] = $id_card;
        if($capital_account) $condition['capital_account'] = $capital_account;

        if(count($condition) < 2) return $this->ajax_return(500, "参数不足");
        $visitor = SysCustomer::where($condition)->first();
        if($visitor) {
            //todo 寻找客户详细信息, 分配客服(这个暂不做)
        } else {
            $visitor = SysCustomer::create($condition);
        }
        $this->customer_id = $visitor->id;

        //存入消息记录主表, 返回id
        $record = SysRecord::create(['customer_id' => $this->customer_id]);

        $this->record_id = $record->id;

        //加入在线用户列表
        $this->joinOnlineVisitors($visitor);

        // 通知所有客服
        $this->noticeOnlineManager('customer_add');

        // 返回订阅的频道
        $channel = $this->channel_prefix . $this->customer_id;

        $info = [
            'channel' => $channel,
            'event' => $this->event_name,
            'customer_id' => $this->customer_id,
            'href' => '/call_center/chat',
            'record_id' => $record->id
        ];
        return $this->ajax_return(200, "success", [
            'info' => $info,
            'config' => $this->getConfig(),
        ]);
    }

    /**
     * 加入在线客户列表(redis)
     * @param $visitor
     * @todo 获取用户详细信息并保存
     */
    private function joinOnlineVisitors($visitor)
    {
        $data = [
            'record_id' => $this->record_id,
            'customer_avatar' => $this->avatar,
            'customer_id' => $visitor->id,
            'customer_name' => $visitor->name,
            'status' => 1,
        ];
        Redis::hSet(self::ONLINE_CUSTOMER_LIST, $visitor->id, json_encode($data));
    }

    /**
     * 在线客户中剔除客户
     * @param $customer_id
     */
    public function leaveOnlineVisitors($customer_id)
    {
        Redis::hDel(self::ONLINE_CUSTOMER_LIST, $customer_id);
    }

    /**
     * 获取在线客户列表
     */
    public function getOnlineCustomerList(){
        $list = Redis::hGetAll(self::ONLINE_CUSTOMER_LIST);
        if(!$list) return $this->ajax_return(500,'没有在线用户');
        $result = [];
        foreach ($list as $k => $v) {
            $result[] = json_decode($v);
        }
        return $this->ajax_return(200, 'success', $result);
    }

    /**
     * 客户发送消息
     * @param Request $request
     */
    public function sendByCustomer(Request $request){
        $customer_id = $request->post('customer_id');
        $manager_id = $request->post('manager_id');
        $content = $request->post('content');
        $type = $request->post('type'); //类型
        $this->customer_id = $customer_id;
        $this->storeMessage($request->post());
        if(!$manager_id) {
            //客服id不存在, 自动回复
            $this->autoReply($type, $content);
        } else {
            $data = $this->messagePackaging($this->customer_id, $manager_id, 'customer','manager', $content, 'message');
            broadcast(new CallCenterCustomerEvent($data));
        }
    }


}