<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Events\CallCenterCustomerChangeEvent;
use App\Events\CallCenterCustomerEvent;
use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Base\CallCenter\SysRecordDetail;
use App\Models\Admin\Base\CallCenter\SysSetting;
use App\Models\Admin\Base\CallCenter\SysTemplate;
use App\Models\Admin\Base\CallCenter\SysManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * 客服中心模块基类
 * Class BaseController
 * @package App\Http\Controllers\Admin\CallCenter
 */
class BaseController extends BaseAdminController
{

    const CATEGORY_EVENT = 'event';

    const CATEGORY_MESSAGE = 'message';

    const ONLINE_CUSTOMER_LIST = "ONLINE_CUSTOMER_LIST"; // 在线客户列表

    const ONLINE_MANAGER_LIST = "ONLINE_MANAGER_LIST"; // 在线客服列表

    public $customer_id;

    public $channel_prefix = "customer_";     // 频道前缀

    public $event_name = 'CallCenterCustomerEvent';  //事件名

    public $record_id;

    public $avatar;

    public $folder = "";

    public $config_key = "call_center_configs";

    public function __construct()
    {
        parent::__construct();
//        $this->getConfig();
    }

    /**
     * 获取客服中心配置文件
     */
    public function getConfig(){
        if (!Cache::has($this->config_key)) {
            $this->setConfig();
        }
        $cache = Cache::get($this->config_key);
        foreach ($cache as $v) {
            $config[$v['name']] = $v['value'];
        }
        return $config;
    }

    /**
     * 修改配置
     */
    public function setConfig(){
        Cache::flush();
        $sysConfigs = SysSetting::where('status', 1)->get();
        if($sysConfigs){
            Cache::add($this->config_key, $sysConfigs,3600);
        }
    }

    /**
     * 文件上传
     * @param Request $request
     * @return array
     */
    public function upload(Request $request){
        $config = $this->getConfig();
        $support_file_suffix = $config['support_file_suffix'];
        $support_file_suffix = explode(',', $support_file_suffix);
        $kb = $config['max_upload_file_size'];
        $max_upload_file_size = $kb*1024;
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $ext = pathinfo($name)['extension'];
        if(!in_array($ext, $support_file_suffix)) {
            return $this->ajax_return(500, "禁止上传的文件格式");
        }
        if($file->getSize() > $max_upload_file_size) {
            return $this->ajax_return(500, '文件过大, 限制大小'.$kb.'KB');
        }
        $month = date('Ym');
        $day = date('d');
        $core_path = 'callCenter/'.$month.'/'.$day;
        $dir = storage_path('app/public/'.$core_path);
        $filename = time().'-'.$name;
        $file->move($dir, $filename);
        $real_path = $core_path . '/' . $filename;
        return $this->ajax_return(200,'success', [
            'name' => $name,
            'url' => '/storage/'.$real_path
        ]);
    }

    /**
     * 信息包装
     * @param $customer_id
     * @param $manager_id
     * @param string $sender customer || customer || system
     * @param string $receiver customer || customer
     * @param string $content
     * @param string $type event || message || link || file (file的时候要加文件类型)
     * @param string $ext
     * @return array
     */
    public function messagePackaging($customer_id, $manager_id, $sender, $receiver, $content, $type, $ext = '') {
        return $this->packaging(self::CATEGORY_MESSAGE, $customer_id, $manager_id, $sender, $receiver, $content, $type, $ext = '');
    }

    /**
     * 通用包装
     * @param $category
     * @param $customer_id
     * @param $manager_id
     * @param string $sender customer || customer || system
     * @param string $receiver customer || customer
     * @param mixed $content
     * @param string $type event || message || link || file (file的时候要加文件类型)
     * @param string $ext
     * @return array
     */
    public function packaging($category, $customer_id, $manager_id, $sender, $receiver, $content, $type, $ext = ''){
        $data = [
            'category' => $category,
            'type' => $type,
            'customer_id' => $customer_id,
            'manager_id' => $manager_id,
            'sender' => $sender,
            'receiver' => $receiver,
            'content' => $content
        ];
        if($ext) $data['ext'] = $ext;
        return $data;
    }

    /**
     * 事件包装
     * @param $type
     * @param mixed
     * @return array
     */
    public function eventPackaging($type, $data){
        if(!is_array($data)) $data = json_decode($data, true);
        return [
            'category' => self::CATEGORY_EVENT,
            'type' => $type,
            'data' => $data
        ];
    }

    /**
     * 客服之间交流信息包装
     * @param string $category message || event
     * @param int $from
     * @param int $to
     * @param string $type
     * @param string $content
     */
    public function managerPackaging($category, $from, $to, $type, $content){
        return [
            'category' => $category,
            'from' => $from,
            'to' => $to,
            'type' => $type,
            'content' => $content,
        ];
    }

    /**
     * 自动回复
     */
    public function autoReply($type, $content)
    {
        if($type == 'template') {
            $result = SysTemplate::where('id', trim($content))->first();
            if($result) {
                $content = $result->answer;
                $count = $result->count+1;
                SysTemplate::where("id", $result->id)->update(['count' => $count]);
            }
        } else {
            $flag = $this->hasOnlineManager();
            if($flag) {
                $content = "客服连接中, 请稍后";
            } else {
                $content = "当前没有客服在线, 请输入关键词快速获取帮助, 如有疑问请拨打400-882-0628。<br>工作日8.30-17.00";
            }
        }
        $data = $this->messagePackaging($this->customer_id, 0, 'manager', 'customer', $content, 'message');
        broadcast(new CallCenterCustomerEvent($data));
    }

    /**
     * 信息保存
     */
    public function storeMessage($data)
    {
        SysRecordDetail::create($data);
    }

    /**
     * 通知在线客服
     * @param $type
     */
    public function noticeOnlineManager($type)
    {
        $data = Redis::hGet(self::ONLINE_CUSTOMER_LIST, $this->customer_id);
        $data = $this->eventPackaging($type, $data);
        broadcast(new CallCenterCustomerChangeEvent($data));
    }

    /**
     * 通知客服人员 客服事件
     * @param $type
     * @param $data
     */
    public function noticeManager($type, $data)
    {
        $result = $this->eventPackaging($type, $data);
        broadcast(new CallCenterCustomerChangeEvent($result));
    }


    /**
     * 检测客户是否在redis中
     */
    public function checkOnline($customer_id){
        $data = Redis::hGet(self::ONLINE_CUSTOMER_LIST, $customer_id);
        if($data) return true;
        return false;
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
     * 获取客户端类别
     */
    public function getClientGroup($id, $flag = false){
        if(!$flag) {
            $client_group = [
                '94' => '恒生',
                '93' => '恒生',
                '92' => 'CTP',
                '91' => 'CTP',
                '90' => 'CTP',		//CTP
                '61' => '易胜',
                '11' => '飞鼠',
                '10' => '飞创',
                '9' => 'CTP',
                '8' => '易胜',
                '7' => '易胜',
                '6' => '易胜',
                '5' => '易胜',
                '4' => '恒生',			//恒生
                '3' => '大户',
                '2' => '中户',
                '1' => '散户',
                '0' => '恒生'			//恒生
            ];
        } else {
            $client_group = [
                '94' => '达钱艾扬（恒生）',
                '93' => '恒生（开拓者）',
                '92' => '达钱艾扬（CTP）',
                '91' => '开拓者（CTP）',
                '90' => 'ATP客户',		//CTP
                '61' => '大连易胜9.0',
                '11' => '飞鼠',
                '10' => '飞创',
                '9' => 'CTP客户',
                '8' => '郑州易胜8.2',
                '7' => '郑州易胜9.0',
                '6' => '张江易胜9.0',
                '5' => '张江易胜8.2',
                '4' => '增量',			//恒生
                '3' => '大户',
                '2' => '中户',
                '1' => '散户',
                '0' => '普通'			//恒生
            ];
        }
        return isset($client_group[$id]) ? $client_group[$id] : '未知' ;
    }


    /**
     * 判断是否有在线客服
     */
    public function hasOnlineManager(){
        $list = Redis::hKeys(self::ONLINE_MANAGER_LIST); //redis 在线列表
        if(empty($list)) return false;
        $admins = SysManager::where('group_id', 13)->select('sys_admin_id')->get()->toArray(); //所有ib客服列表
        foreach($admins as $v) {
            if(in_array("{$v['sys_admin_id']}", $list)) { //找到表示有客服在线
                return true;
            }
        }
        return false;
    }

}