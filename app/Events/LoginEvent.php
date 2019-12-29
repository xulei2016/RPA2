<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\Admin\Admin\SysAdmin;
use Jenssegers\Agent\Agent;

class LoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
    * @var request request
    */
    protected $request;

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
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($request, $user, $agent, $timestamp)
    {
        $this->user = $user;
        $this->agent = $agent;
        $this->request = $request;
        $this->timestamp = $timestamp;
        $this->ip = $request->getClientIp();
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
     * @return ip 获取IP
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
     * @return void obj 缓存session
     */
    public function cacheToken()
    {
        $token['last_session'] = $this->request->session()->get('_token');
        $id = self::getUser()->id;
        return SysAdmin::where('id', $id)->update($token);
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
