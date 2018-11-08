<?php

namespace App\Http\Controllers\base;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\MessageBag;

class BaseController extends Controller
{
    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
    
    /**
     * Get admin url.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function admin_url($path = '', $parameters = [], $secure = null)
    {
        if (\Illuminate\Support\Facades\URL::isValidUrl($path)) {
            return $path;
        }

        $secure = $secure ?: (config('admin.https') || config('admin.secure'));

        return url(admin_base_path($path), $parameters, $secure);
    }
    
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_base_path($path = '')
    {
        $prefix = '/'.trim(config('admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        return $prefix.'/'.trim($path, '/');
    }
    

    /**
     * Flash a toastr message bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new MessageBag(get_defined_vars());

        session()->flash('toastr', $toastr);
    }
    
    
    /**
     * Flash a success message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_success($title, $message = '')
    {
        admin_info($title, $message, 'success');
    }
    

    /**
     * Flash a error message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_error($title, $message = '')
    {
        admin_info($title, $message, 'error');
    }
    

    /**
     * Flash a warning message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_warning($title, $message = '')
    {
        admin_info($title, $message, 'warning');
    }
    
    
    /**
     * Flash a message bag to session. save once data
     *
     * @param string $title
     * @param string $message
     * @param string $type
     */
    function admin_info($title, $message = '', $type = 'info')
    {
        $message = new MessageBag(get_defined_vars());

        session()->flash($type, $message);
    }
    
    
    /**
     * @param $path
     *
     * @return string
     */
    function admin_asset($path)
    {
        return (config('admin.https') || config('admin.secure')) ? secure_asset($path) : asset($path);
    }
    

    /**
     * Delete from array by value.
     *
     * @param array $array
     * @param mixed $value
     */
    function array_delete(&$array, $value)
    {
        foreach ($array as $index => $item) {
            if ($value == $item) {
                unset($array[$index]);
            }
        }
    }
    
    /**
     * 批接收方法
     * @param $data string 
     * @param $params string 
     * @param $type int 是否允许为空 
     * @param $default bool 默认值 
     * @return array $result
     */
    public function get_params($request ,$params = [], $type = TRUE, $default = null){
        $data = [];
        if(!empty($params)){
            if($type){
                foreach($params as $val){
                    $data[$val] = $request->$val;
                }
            }else{
                foreach($params as $val){
                    if('' != $request->$val && null != $request->$val){
                        $data[$val] = $request->$val;
                    };
                }
            }
        }
        return $data;
    }

	/**
	 * 获取ip地址
	 * @return stirng	
	 */
	public static function getRealIp(){
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = 0; $i < count($ips); $i++) {
				if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
					$ip = $ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	
	/**
	 * 获取ip所属信息
	 * @param string $ip
	 * @return mixed
	 */
	public static function getAreaByIp($ip){
		$taobaoUrl = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
		//{"code":0,"data":{"country":"中国","country_id":"CN","area":"华东","area_id":"300000","region":"安徽省","region_id":"340000","city":"合肥市","city_id":"340100","county":"","county_id":"-1","isp":"电信","isp_id":"100017","ip":"114.97.7.229"}}
        
        //因ip服务地址访问慢，禁用
        $result = [
            "code"=>0,
            "data"=> [
                "ip"=> $ip,
                "country"=> "中国",
                "area"=> "华中",
                "region"=> "安徽省",
                "city"=> "内网IP",
                "county"=> "内网IP",
                "isp"=> "内网IP",
                "country_id"=> "xx",
                "area_id"=> "",
                "region_id"=> "xx",
                "city_id"=> "local",
                "county_id"=> "local",
                "isp_id"=> "local"
            ]
        ];

        // $rawResult = file_get_contents($taobaoUrl);
        // $result = json_decode($rawResult);
		$result = (array)$result;
        $result = (array)$result['data'];
        return $result;
    }

    /**
	 * 判断是否是移动客户端
	 * @return $isMobile
	 */
	public function isMobile() {
        $data['isMobile'] = FALSE;
        $data['userAgent'] = '';
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (empty($user_agent)) {
            $data['isMobile'] = false;
		} else {
            // 移动端UA关键字
			$mobile_agents = array (
                'Mobile',
                'Android',
                'Silk/',
                'Kindle',
                'BlackBerry',
                'Opera Mini',
                'Opera Mobi'
			);
			$data['isMobile'] = false;
			foreach ($mobile_agents as $device) {
                if (strpos($user_agent, $device) !== false) {
                    $data['isMobile'] = true;
					$data['userAgent'] = $user_agent;
					break;
				}
			}
            $data['userAgent'] = $this->get_browser();
		}
		return $data;
    }
    
    /**
     * 浏览器类型
     * @return string $result
     */
    public function get_browser(){
        if((false == strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) && ( FALSE !== strpos($_SERVER['HTTP_USER_AGENT'], 'Trident'))){
            return 'Internet Explorer 11.0';
        }
        $bro_array = [
            'MSIE 10.0' => 'Internet Explorer 10.0',
            'MSIE 9.0' => 'Internet Explorer 9.0',
            'MSIE 8.0' => 'Internet Explorer 8.0',
            'MSIE 7.0' => 'Internet Explorer 7.0',
            'MSIE 6.0' => 'Internet Explorer 6.0',
            'Edge' => 'Edge',
            'Firefox' => 'Firefox',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Opera' => 'Opera',
            '360SE' => '360SE',
            'MicroMessage' => 'MicroMessage',
        ];
        foreach($bro_array as $k=>$v){
            if((TRUE == strpos($_SERVER['HTTP_USER_AGENT'],$k))){
                return $v; break;
            }
        }
        return 'idontknow';
    }

    /**
	 * 生成GUID
	 */
	public static function guid() {		
		if (function_exists ( 'com_create_guid' )) {
			$guid= com_create_guid ();
		} else {
			mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
			$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
			$hyphen = chr ( 45 ); // "-"
			$uuid = chr ( 123 ) . 			// "{"
			substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 ) . chr ( 125 ); // "}"
			$guid=$uuid;			
		}
		return substr($guid, 1,36);
	}
	
	/**
	 * 获取当前时间
	 * @return string time
	 */
	public static function getTime(){
	    return date('Y-m-d H:i:s',time());
    }
    	
	/**
	 * 构造分页页面提交数据
	 * @param object $controller
	 * @param array $keyValueList array('name'=>'like','add_time'=>'>=','sort'=>'=')
	 * @return array
	 */
	protected function getPagingList($data,$keyValueList){
        $conditionList = array();
        $value = '';
		foreach ($keyValueList as $key=>$operator){
            $value = $data[$key];
			if(null != $value && '' != $value){
                $dateKey = $this->isDateArea($key);
				if($dateKey){//判断是否时间段
					array_push($conditionList,  array($dateKey, $operator, $value));
				}else{
					array_push($conditionList,  array($key, $operator, $value));
				}
			}
		}
		return $conditionList;
    }

    //获取数组数据
    public function get_one($data){
        foreach($data as $param){
            yield $param;
        }
    }
    	
	/**
	 * 判断是否是时间区域
	 * @param string $key
	 * @return boolean
	 */
	private function isDateArea($key){
		$keyList = explode('_',$key);
		if($keyList[0] == 'from' || $keyList[0] == 'to'){
			unset($keyList[0]);
			$newKey = implode('_',$keyList);
			return $newKey;
		}else{
			return false;
		}
    }

    /**
     * 删除图片
     * @param string url
     * @return bool return
     */
    public function unlinkImg($url){
        $url = '.'.$url;
        if(file_exists($url)){
            unlink($url);
            return true;
        }
        return false;
    }
        
    /**
     * ajax返回值信息
     * @return array [code 状态码 200/500/ info 提示信息 data 返回数据] 
     */
    public function ajax_return($code, $info = null, $data = []){
        return array(
            'code' => $code,
            'info' => $info,
            'data' => $data
        );
    }
}
