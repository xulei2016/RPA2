<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Events\CallCenterCustomerChangeEvent;
use App\Models\Admin\Base\CallCenter\SysManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ChatRoomController extends BaseController
{
    private $view_prefix = 'admin.base.callCenter.chatRoom.';

    /**
     * 聊天室
     * @return mixed
     */
    public function index(){
        $manager = auth()->guard()->user();
        $id = $manager->id;
        $id = 1;
        $manager_detail = SysManager::where('sys_admin_id', $id)->first();
        // if(!$manager_detail) return view('errors.403_extend');
        $result = [
            'id' => $id,
            'head_img' => $manager->head_img,
            'realName' => $manager->realName,
            'manager_id' => $manager_detail->id,
            'nickname' => $manager_detail->nickname,
            'label' => $manager_detail->label,
            'desc' => $manager_detail->desc,
            'status' => 1,  // 在线
        ];
        $this->addOnlineManagerList($result);
        return view($this->view_prefix.'index', ['manager' => $result]);
    }

    /**
     * 加入在线客服列表, 并通知
     * @param $result
     */
    public function addOnlineManagerList($result){
        // 通知其他人  该客服离开
        $this->noticeManager('manager_remove', $result);
        $this->noticeManager('manager_add', $result);
        Redis::hSet(self::ONLINE_MANAGER_LIST, $result['id'], json_encode($result));
    }
}