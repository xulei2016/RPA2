<?php

namespace App\Listeners;

use App\Events\LoginEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GeoIp2\Database\Reader;
use App\Models\Admin\Base\Sys\SysLoginRecord;

/**
 * LoginListener class
 * 
 * @author hsyLay
 * @since 1.0.0
 */
class LoginListener// implements ShouldQueue 
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LoginEvent $event
     * @return void
     * @throws \GeoIp2\Exception\AddressNotFoundException
     * @throws \MaxMind\Db\Reader\InvalidDatabaseException
     */
    public function handle(LoginEvent $event)
    {
        //获取事件中保存的信息
        $agent = $event->getAgent();
        $ip = $event->getIp();

        //登录信息
        $login_info = [
            'ip' => $ip,
            'login_time' => $event->getTimestamp(),
            'user_id' => $event->getUser()->id
        ];

        //单客户登录
        if($event->getUser()->login_protected){
            $event->cacheToken();
        }

        //geoip
        $geoip = [];
        if(!in_array($ip, ['127.0.0.1', '::1'])){
            $reader = new Reader('GeoLite2-City.mmdb');
            $record = $reader->city($ip);
            $geoip = [
                'isoCode' => $record->country->isoCode,
                'country_2' => $record->country->name,
                'region_2' => $record->mostSpecificSubdivision->name,
                'city_2' => $record->city->name,
                'postal' => $record->postal->code,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
                'traits_net' => $record->traits->network
            ];
        }

        //taobao ip
        $address = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
        $address = $address ? json_decode($address, true) : [] ;

        // jenssegers/agent 的方法来提取agent信息
        //设备名称
        $login_info['device'] = $agent->device();
        //浏览器
        $browser = $agent->browser();        
        $login_info['browser'] = $browser . ' ' . $agent->version($browser);
        
        //操作系统
        $platform = $agent->platform();
        $login_info['platform'] = $platform . ' ' . $agent->version($platform);

        //语言
        $login_info['language'] = implode(',', $agent->languages());

        //设备信息
        $info = array_merge($login_info, self::deviceType($agent));
        //地址信息1--淘宝
        $info = array_merge($info, $address['data']);
        //地址信息1--geoip
        $info = array_merge($info, $geoip);
        
        SysLoginRecord::create($info);
    }

    /**
     * deviceType function
     *
     * @param [type] $agent
     * @return void
     */
    private function deviceType($agent)
    {
        //设备类型
        if ($agent->isTablet()) {

            // 平板
            $info['device_type'] = 'tablet';
        } else if ($agent->isMobile()) {

            // 便捷设备
            $info['device_type'] = 'mobile';
        } else if ($agent->isRobot()) {

            // 爬虫机器人
            $info['device_type'] = 'robot';
            $info['device'] = $agent->robot(); //机器人名称
        } else {

            // 桌面设备
            $info['device_type'] = 'desktop';
        }

        return $info;
    }
}
