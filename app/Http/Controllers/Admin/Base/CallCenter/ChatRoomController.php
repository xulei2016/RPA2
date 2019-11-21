<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Events\CallCenterCustomerChangeEvent;
use App\Models\Admin\Base\CallCenter\SysManager;
use App\Models\Admin\Base\CallCenter\SysCustomer;
use App\Models\Admin\Base\CallCenter\SysRecordDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ChatRoomController extends BaseController
{
    private $view_prefix = 'admin.base.callCenter.chatRoom2.';

    /**
     * 聊天室
     * @return mixed
     */
    public function index(){
        $id = auth()->guard()->user()->id;
        $chatList = SysRecordDetail::where('sys_call_center_record_details.manager_id', $id)
            ->rightJoin('sys_call_center_customers', 'sys_call_center_record_details.customer_id', '=', 'sys_call_center_customers.id')
            ->select(['sys_call_center_customers.*', 'sys_call_center_record_details.content', 'sys_call_center_record_details.created_at','sys_call_center_record_details.id as rid'])
            ->orderBy('sys_call_center_customers.status', 'desc')
            ->orderBy('sys_call_center_record_details.created_at', 'desc')
            ->groupBy('sys_call_center_record_details.customer_id')
            ->paginate(10);
        // dd($chatList);
        foreach($chatList as &$list){
            $list->content = self::type_filter($list->content);
        }
        return view($this->view_prefix.'index', ['chatList' => $chatList]);
    }

    /**
     * 聊天室
     * @return mixed
     */
    public function index2(){
        $manager = auth()->guard()->user();
        $id = $manager->id;
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

    /**
     * 匹配类型
     */
    private function type_filter($str){
        $array = [
            ['/<div><a .*? download.*?>.*?<\/a><\/div>/', '[文件]'],
            ['/<div.*?<audio src=.*?><\/audio>.*?<\/div>/', '[语音]'],
            ['/<img src=.*?emoji\/emo_.*?>/', '[表情]'],
            ['/<img .*?src=[\'"]([^\'"]+)[^>]*>/', '[图片]'],
            ['/^<a[^<>]+>.+?<\/a>$/', '[超链接]'],
        ];
        foreach($array as $v){
            if(preg_match($v[0], $str)){
                $str = $v[1];
                break;
            }
        }
        return $str;
    }
}