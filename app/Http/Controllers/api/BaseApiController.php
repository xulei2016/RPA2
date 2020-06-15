<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\base\BaseController;
use App\Models\Admin\Api\ApiList;
use App\Models\Admin\Api\ApiLog;
use App\Models\Admin\Base\SysSmsLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BaseApiController extends BaseController
{
    
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
        return ApiLog::create($log);
    }

    /**
     * 接口黑白名单验证
     * @param $api
     * @param $ip
     * @return array|bool
     */
    protected function check_ip($api,$ip)
    {
        return true;
        //获取黑白名单，先取缓存
        if (!Cache::has($api)) {
            $sysapiip = ApiList::where([['name','=',$api],['status','=',1]])->first();
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
        // $black_list = array_keys($sysapiip->black_list ? json_decode($sysapiip->black_list,true) : []);
        // $white_list = array_keys($sysapiip->white_list ? json_decode($sysapiip->white_list,true) : []);
        
        // //有白名单存在，请求ip必须在白名单中
        // if($white_list && !in_array($ip,$white_list)){
        //     $data = [
        //         'status' => 450,
        //         'msg' => "ip限制访问"
        //     ];
        //     return $data;
        // }
        // //有黑名单存在，请求ip必须不在黑名单中
        // if($black_list && in_array($ip,$black_list)){
        //     $data = [
        //         'status' => 450,
        //         'msg' => "ip限制访问"
        //     ];
        //     return $data;
        // }
        return true;
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
