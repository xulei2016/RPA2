<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\base\BaseController;
use App\Models\Admin\Api\RpaApiIp;
use App\Models\Admin\Api\RpaApiLog;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Base\SysSmsLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        $this->smsLog('中正云', $phone, iconv("gb2312","utf-8",$msg), $status);

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
        $this->smsLog('优信', $phone, iconv("gb2312","utf-8",$msg), $body);

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
            'type' => $type,
            'api' => 'sms',
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
        $name = isset(Auth::user()->name) ? Auth::user()->name : $ip;
        $log = [
            'api' => $function,
            'param' => json_encode($request->all(),true),
            'return' => $data,
            'ip' => $name
        ];
        return RpaApiLog::create($log);
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
            $sysapiip = RpaApiIp::where([['api','=',$api],['state','=',1]])->first();
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
        $flag = false;
        $log = "000";
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img, $result)) {

            $type = $result[2];//图片后缀
            $new_file = "d:/uploadFile".$path;
            if (!is_dir($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700, true);
            }
            if($filename == ''){
                $filename = time() . '_' . uniqid() . ".{$type}";
            }
            $new_file = $new_file . $filename;

            //写入操作
            if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img)))){
                $log = $filename;
                $flag = true;
            }else{
                $log = '图片保存失败';
            }
        }else{
            $log = 'base64解析失败';
        }
        return ['log_img'=>$log, 'filename'=>$filename, 'flag'=>$flag, 'result'=>$result];
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

    /** 图片转二进制
     * @param  String $img_file 文件路径
     * @return String binary string
     */
    function binaryEncodeImage($img_file) {
        $p_size = filesize($img_file);
        $img_binary = fread(fopen($img_file, "r"), $p_size);
        return $img_binary;
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
        $sql = "select * from rpa_clock_list where created_at in (select MAX(created_at) from rpa_clock_list GROUP BY host)";
        $res = DB::select($sql);
        $body = ['code' => 200,'info' => $res];
        return $body;
    }

    /** http请求
     * 
     */
    public function http($url, $method, $postfields = NULL, $headers = array(),$outputHeader=false) {
	
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($ci, CURLOPT_TIMEOUT, 60);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_ENCODING, "");
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
	
		if($outputHeader){
	
			curl_setopt($ci, CURLOPT_HEADER, true);
		}
		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
	
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
			case 'GET':
				curl_setopt($ci, CURLOPT_HTTPGET, true);
		}
	
	
		curl_setopt($ci, CURLOPT_URL, $url );
		curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
	
		$response = curl_exec($ci);
	
		curl_close ($ci);
		return $response;
    }
    
    /**
     *  根据身份证号码计算年龄
     *  @param string $idcard    身份证号码
     *  @return int $age
     */
    public function get_age($idcard){
        if(empty($idcard)) {
            return null;
        }
        //  获得出生年月日的时间戳
        $date = strtotime(substr($idcard,6,8));
        //  获得今日的时间戳
        $today = strtotime('today');
        //  得到两个日期相差的大体年数
        $diff = floor(($today-$date)/86400/365);
        //  strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($idcard,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
        return $age;
    }
}