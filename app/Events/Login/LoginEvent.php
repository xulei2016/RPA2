<?php

namespace App\Events\Login;

use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

use App\Models\Admin\Admin\SysAdmin;
use App\Notifications\Login\SingleLogin;
use Jenssegers\Agent\Agent;

class LoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
    * @var request request
    */
    public $request;

    /**
    * @var User 用户模型
    */
    protected $user;

    /**
    * @var Agent Agent对象
    */
    protected $agent;

    /**
    * @var string IP地址
    */
    protected $ip;

    /**
    * @var int 登录时间戳
    */
    protected $timestamp;

    /**
    * @var int 登录状态
    */
    protected $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request, $user, $agent, $timestamp, $ip, $status)
    {
        $this->user = $user;
        $this->agent = $agent;
        $this->status = $status;
        $this->request = $request;
        $this->timestamp = $timestamp;
        $this->ip = $ip;
    }

    /**
     * @return User 获取用户
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Agent 获取代理信息
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return int 获取时间戳
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int 获取登录状态
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * @return void obj 缓存session
     */
    public function cacheToken()
    {
        $token['last_session'] = $this->request->session()->get('_token');
        $id = self::getUser()->id;
        return SysAdmin::where('id', $id)->update($token);
    }

    public function rememberLogin(){
        $user = auth()->guard('admin')->user();
        $token = $this->request->session()->get('_token');
        $token = Crypt::encryptString($token);
        $user->notify(new SingleLogin($token));
//        Notification::send($user, new SingleLogin($token));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

}
