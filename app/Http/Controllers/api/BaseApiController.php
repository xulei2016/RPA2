<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\base\BaseController;
use App\Models\Admin\Base\SysApiIp;
use App\Models\Admin\Base\SysApiLog;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Base\SysSmsLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Admin\Rpa\rpa_accesstoken;

class BaseApiController extends BaseController
{
    /**
     * 中正云短信接口
     * @param   [String]  $phone    手机号 多个手机号用“,”分割
     * @param   [String]  $msg      发送内容
     * @return  [Integer] $code     状态码
     * @return  [String]  $data     返回信息
     */
    protected function zzy_sms($phone,$msg)
    {
        $msg = iconv("utf-8","gb2312",$msg);

        $zzy = config('sms.ZZY');
        $url = $zzy['url']['mult'];
        $statuses = $zzy['status'];

        $form_params = [
            'id' => $zzy['account'],
            'pwd' => $zzy['password'],
            'to' => $phone,
            'content' => $msg,
        ];

        $guzzle = new Client();
        $response = $guzzle->post($url, [
            'form_params' => $form_params
        ]);
        $body = $response->getBody();

        $status = explode('/',(string)$body)[0];

        $data = [
            'status' => $status,
            'msg' => $statuses[$status]
        ];

        $this->smsLog('中正云', $phone, $msg, $status);

        return $data;
    }

    /**
     * 优信短信接口
     * @param   [String]  $phone    手机号
     * @param   [String]  $msg      发送内容
     * @return  [Integer] $code     状态码
     * @return  [String]  $data     返回信息
     */
    protected function yx_sms($phone,$msg)
    {
        $msg = iconv("utf-8","gb2312",$msg);

        $yx = config('sms.YX');
        $url = $yx['url']['mult'];
        $statuses = $yx['status'];

        $form_params = [
            'CorpID' => $yx['account'],
            'Pwd' => $yx['password'],
            "Mobile" => $phone,
            "Content" => $msg,
            "Cell" => '',
            "SendTime" => ''
        ];

        $guzzle = new Client();
        $response = $guzzle->post($url, [
            'form_params' => $form_params
        ]);
        $body = $response->getBody();
        $body = (string)$body;

        $data = [
            'status' => $body,
            'msg' => $statuses[$body]
        ];

        //短信日志
        $this->smsLog('优信', $phone, $msg, $body);

        return $data;
    }

    /**
     * 短信发送日志
     * @Description 短信日志
     * @param [string] $type
     * @param [string] $phone
     * @param [string] $content
     * @param [string] $status
     * @return void bool
     */
    private function smsLog($type, $phone, $content, $status){
        $sms = [
            'api' => $type,
            'phone' => $phone,
            'content' => $content,
            'return' => $status,
        ];
        return SysSmsLog::create($sms);
    }

    /**
     * api调用日志
     * @param string $function
     * @param Request $request
     * @param string $data
     * @param string $ip
     * @return mixed
     */
    protected function apiLog(string $function, Request $request, string $data, string $ip){
        $log = [
            'api' => $function,
            'param' => $request,
            'return' => $data,
            'ip' => $ip
        ];
        return SysApiLog::create($log);
    }

    /**
     * 接口黑白名单验证
     * @param $api
     * @param $ip
     * @return array|bool
     */
    protected function check_ip($api,$ip)
    {
        //获取黑白名单，先取缓存
        if (!Cache::has($api)) {
            $sysapiip = SysApiIp::where([['api','=',$api],['state','=',1]])->first();
            if(!$sysapiip){
                $data = [
                    'status' => 403,
                    'msg' => "接口未注册,或被禁用"
                ];
                return $data;
            }
            Cache::add($api,$sysapiip,3600);
        }else{
            $sysapiip = Cache::get($api);
        }
        //判断
        $black_list = array_keys($sysapiip->black_list ? json_decode($sysapiip->black_list,true) : []);
        $white_list = array_keys($sysapiip->white_list ? json_decode($sysapiip->white_list,true) : []);
        //有白名单存在，请求ip必须在白名单中
        if($white_list && !in_array($ip,$white_list)){
            $data = [
                'status' => 450,
                'msg' => "ip限制访问"
            ];
            return $data;
        }
        //有黑名单存在，请求ip必须不在黑名单中
        if($black_list && in_array($ip,$black_list)){
            $data = [
                'status' => 450,
                'msg' => "ip限制访问"
            ];
            return $data;
        }
        return true;
    }

    /**
     * base64转图片文件
     * @param $img
     * @param string $path
     * @param string $filename
     * @return string
     */
    protected function base64ToImage($img, $path = '/',$filename= ''){
        $log = "000";
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)) {

            $type = $result[2];//图片后缀
            $new_file = "d:/uploadFile".$path;
            if (!is_dir($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            if($filename == ''){
                $filename = time() . '_' . uniqid() . ".{$type}";
            }
            $new_file = $new_file . $filename;

            //写入操作
            if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img)))){
                $log = $filename;
            }else{
                $log = '图片保存失败';
            }
        }else{
            $log = 'base64解析失败';
        }
        return ['log_img'=>$log, 'filename'=>$filename, 'result'=>$result];
    }


    /** 图片文件转base64
     * @param  String $file 文件路径
     * @return String base64 string
     */
    protected function base64EncodeImage ($image_file) {
        if(!file_exists($image_file)){
            return false;
        }
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    /**
     * 获取配置
     * @param $item_keys  需要的配置key
     * @return array      ip对应的服务器名 或者配置key对应的value
     */
    public function get_config($item_keys = [])
    {
        $return = [];
        //获取配置信息
        if (!Cache::has("sysConfigs")) {
            $sysConfigs = SysConfig::get();
            Cache::add("sysConfigs",$sysConfigs,3600);
        }else{
            $sysConfigs = Cache::get("sysConfigs");
        }

        //不传值，代表获取当前服务器名
        if(empty($item_keys)){
            $ip = $_SERVER['LOCAL_ADDR'];
            foreach($sysConfigs as $config){
                if($ip == $config->item_value){
                    $return = $config->item_key;
                    break;
                }
            }
            return $return;
        }

        foreach($sysConfigs as $config){
            foreach($item_keys as $k){
                if($config->item_key == $k){
                    $return[$k] = $config->item_value;
                }
            }
        }
        return $return;
    }

    /**
     * 获取打卡信息
     * @return mixed|\Psr\Http\Message\StreamInterface
     */
    public function getCard()
    {
        $guzzle = new Client();
        $response = $guzzle->get('http://172.16.253.26/rpa/clock/get.php',[]);
        $body = $response->getBody();
        $body = json_decode((string)$body,true);
        return $body;
    }

    /**
     * 获取api接口的access_token
     * @param $host
     * @return mixed
     */
    public function access_token($host)
    {
//        查看数据库是否有该token
        $accesstoken = rpa_accesstoken::where("username","=","web@example.com")->first();
        if($accesstoken){
//            判断是否过期
            if(time() - strtotime($accesstoken['updated_at']) < $accesstoken['timeout']){
                return $accesstoken['token'];
            }else{
//                刷新token，并且更新数据库
                $url = $host."/oauth/token";
                $guzzle = new Client();
                $response = $guzzle->post($url, [
                    'form_params' => [
                        'grant_type' => 'refresh_token',
                        'refresh_token' => $accesstoken['refresh_token'],
                        'client_id' => 2,
                        'client_secret' => 'DuNMC6w9faxgeRx1g1eTC5N3lGvukbNiERAI7Jya'
                    ],
                ]);
                $body = $response->getBody();
                $body = json_decode((string)$body,true);

                $data = [
                    'token' => $body['name']." ".$body['access_token'],
                    'updated_at' => date("Y-m-d H:i:s",time()),
                    'timeout' => $body['explear_at'],
                    'refresh_token' => $body['refresh_token'],
                    'username' => 'rap@example.com'
                ];
                rpa_accesstoken::where("id",$accesstoken['id'])->update($data);
                return $data['token'];
            }
        }else{
            //获取token存入数据库
            $url = $host."/oauth/token";
            $guzzle = new Client();
            $response = $guzzle->post($url, [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => 2,
                    'client_secret' => 'DuNMC6w9faxgeRx1g1eTC5N3lGvukbNiERAI7Jya',
                    'username' => 'web@example.com',
                    'password' => 'H@qh9772rpa,.',
                    'scope' => ''
                ],
            ]);
            $body = $response->getBody();
            $body = json_decode((string)$body,true);

            $data = [
                'token' => $body['name']." ".$body['access_token'],
                'updated_at' => date("Y-m-d H:i:s",time()),
                'timeout' => $body['explear_at'],
                'refresh_token' => $body['refresh_token'],
                'username' => 'rap@example.com'
            ];
            rpa_accesstoken::create($data);
            return $data['token'];
        }

    }
    
}
