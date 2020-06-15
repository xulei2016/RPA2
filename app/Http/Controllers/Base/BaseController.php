<?php

namespace App\Http\Controllers\base;

use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Admin\Base\SysConfig;
use App\Models\Admin\Base\Sys\SMS\SysSmsSetting;
use App\Http\Controllers\Controller;
use App\Models\Admin\Rpa\rpa_accesstoken;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\MessageBag;
use GuzzleHttp\Client;
use SMS;

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

        return url($this->admin_base_path($path), $parameters, $secure);
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
        $this->admin_info($title, $message, 'success');
    }
    

    /**
     * Flash a error message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_error($title, $message = '')
    {
        $this->admin_info($title, $message, 'error');
    }
    

    /**
     * Flash a warning message bag to session.
     *
     * @param string $title
     * @param string $message
     */
    function admin_warning($title, $message = '')
    {
        $this->admin_info($title, $message, 'warning');
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
     * @param $params string || array [param val, default val]
     * @param $type int 是否允许为空 
     * @return array $result
     */
    public static function get_params($request ,$params = [], $type = TRUE){
        $data = [];
        if(!empty($params)){
            foreach($params as $val){
                if(is_array($val)){
                    $v = $val[0];
                    if('' != $request->$v && null != $request->$v){
                        $data[$val[0]] = $request->$v;
                    }else{
                        $data[$val[0]] = $val[1];
                    }
                }else{
                    if($type){
                        $data[$val] = $request->$val;
                    }else{
                        if('' != $request->$val && null != $request->$val){
                            $data[$val] = $request->$val;
                        };
                    }
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
     * 分割时间
     * @param string start_time
     * @param string end_time
     * @param int mins
     * @return json
     */
    public static function slice_time($data = []){
        if(count($data) < 3 || intval($data['mins']) <= 0){
            throw new \Exception('参数错误！！');
        }
        $timeList = [];
        $mins = $data['mins'];
        $new_time = $data['start_time'];
        while(strtotime($new_time) <= strtotime($data['end_time'])){
            $timeList[] = $new_time;
            $new_time = date('H:i:s', strtotime('+'.$mins.' minute', strtotime($new_time)));
        }
        return implode(',', $timeList);
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
                $value = (trim($operator) == 'like') ? "%$value%" : $value ;
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
     * @param $code
     * @param null $info
     * @param array $data
     * @return array [code 状态码 200/500/ info 提示信息 data 返回数据]
     */
    public function ajax_return($code, $info = null, $data = []){
        return array(
            'code' => $code,
            'info' => $info,
            'data' => $data
        );
    }

    /**
     * 文件上传
     * @param $file  文件
     * @param $folder  文件夹
     * @param $allowed_ext   允许上传类型
     * @param bool $old_filename  是否用原文件名
     * @return bool|string
     */
    public function uploadFile($file, $folder, $allowed_ext,$old_filename = true)
    {
        // 构建存储的文件夹规则，值如：uploads/mail/201709/21/
        $folder_name = "uploads/$folder/" . date("Ym/d", time());

        // 值如：f:/RPA2/public/uploads/mail/201709/21/
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
        if($old_filename){
            $filename = time() . '_' . $file->getClientOriginalName();
        }else{
            // 值如：1493521050_7BVc9v9ujP.png
            $filename = time() . '_' . str_random(10) . '.' . $extension;
        }
        // 如果上传的不是图片将终止操作
        if ( ! in_array($extension, $allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        return $upload_path."/".$filename;
    }

    /**
     * 从crm中间件获取数据
     * 
     */
    public function getCrmData($post_data){
        $guzzle = new Client();
        $response = $guzzle->post('www.localhost.com:9102/index.php',[
            'form_params' => $post_data,
            'synchronous' => true,
            'timeout' => 0,
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        return $result;
    }
    
    /**
     * 从crm中间件获取数据
     * 
     */
    public function getCrmData2($post_data){
        $guzzle = new Client();
        $response = $guzzle->post('www.localhost.com:1234/index.php',[
            'form_params' => $post_data,
            'synchronous' => true,
            'timeout' => 0,
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        return $result;
    }

    /**
     * 获取配置
     * @param array $item_keys 需要的配置key
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
     * 获取api接口的access_token
     * @param $host
     * @return mixed
     */
    public function access_token($host)
    {
//        查看数据库是否有该token
        $accesstoken = rpa_accesstoken::where("username","=","web@example.com")->first();
        if($accesstoken){
            //是否过期
            $time = $accesstoken['timeout'] - (time() - strtotime($accesstoken['updated_at']));
//            判断是否过期
            if($time > 0){
                return $accesstoken['token'];
            }else{
                $data = $this->get_token($host);
                rpa_accesstoken::where('id',$accesstoken['id'])->update($data);
                return $data['token'];
            }
        }else{
            $data = $this->get_token($host);
            rpa_accesstoken::create($data);
            return $data['token'];
        }

    }

    public function get_token($host){
        //获取token存入数据库
        $url = $host."/oauth/token";
        $guzzle = new Client(['verify'=>false]);
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
            'token' => $body['token_type']." ".$body['access_token'],
            'updated_at' => date("Y-m-d H:i:s",time()),
            'timeout' => $body['expires_in'],
            'refresh_token' => $body['refresh_token'],
            'username' => 'web@example.com'
        ];
        return $data;
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

    /**
     * 获取所有业务部门
     * @return mixed
     */
    public function getDept()
    {
        $dept = SysDept::where('is_business',1)
            ->orderBy('order','desc')
            ->orderBy('id','asc')
            ->get(['id','name']);
        return $dept;
    }

    /**
     * 获取百度access_token
     * @return mixed
     */
    public function getBaiduAccessToken()
    {
        $guzzle = new Client(['verify' => false]);
        $url = config('baidu_api.token_url');
        $response = $guzzle->get($url, [
            'query' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('baidu_api.API_KEY'),
                'client_secret' => config('baidu_api.SECRET_KEY'),
            ]
        ]);
        $body = $response->getBody();
        $body = json_decode((string)($body),true);
        return $body['access_token'];
    }

    /**
     * 身份证识别
     * @param $image 图片base64
     * @param $id_card_side 身份证正反面 	front：身份证含照片的一面；back：身份证带国徽的一面
     */
    public function idCardOCR($image_path,$id_card_side)
    {
        //图片base编码
        if(!file_exists($image_path)){
            return false;
        }
        $image = file_get_contents($image_path);
        $image = base64_encode($image);

        $guzzle = new Client();
        $url = config('baidu_api.idCard_OCR.url');
        $token = $this->getBaiduAccessToken();
        $url = $url."?access_token=".$token;
        $response = $guzzle->request('POST',$url,[
            'form_params' => [
                'image' => $image,
                'id_card_side' => $id_card_side,
                'detect_direction' => config('baidu_api.idCard_OCR.detect_direction'),
                'detect_risk' => config('baidu_api.idCard_OCR.detect_risk'),
                'detect_photo' => config('baidu_api.idCard_OCR.detect_photo'),
                'detect_rectify' => config('baidu_api.idCard_OCR.detect_rectify'),
            ]
        ]);

        $body = $response->getBody();
        $body = json_decode((string)($body),true);
        return $body;
    }

    // 银行卡识别
    public function bankCardOCR($path) {
        //图片base编码
        if(!file_exists($path)){
            return false;
        }
        $image = file_get_contents($path);
        $image = base64_encode($image);

        $guzzle = new Client([
            'verify' => false
        ]);
        $url = config('baidu_api.bankCard_OCR.url');
        $token = $this->getBaiduAccessToken();
        $url = $url."?access_token=".$token;
        $response = $guzzle->request('POST',$url,[
            'form_params' => [
                'image' => $image,
                'detect_direction' => config('baidu_api.bankCard_OCR.detect_direction')
            ]
        ]);
        $body = $response->getBody();
        $body = json_decode((string)($body),true);
        return $body;
    }

    /**
     * 获取居间人培训时长
     * @param $name 姓名
     * @param $number 编号
     */
    public function getLengthOfMediatorTraining($name, $number){
        //去crm查询居间人的协议日期
        $post_data = [
            'type' => 'jjr',
            'action' => 'getJjrBy',
            'param' => [
                'table' => 'JJR',
                'by' => [
                    ['BH','=',$number],
                    ['XM','=',$name]
                ],
                'columns' => ['XYKSRQ','XYJSRQ']
            ]

        ];
        $res = $this->getCrmData($post_data);
        if($res){
            $xyksrq = strtotime($this->crmDateFormat($res[0]['XYKSRQ']));
            $xyjsrq = strtotime($this->crmDateFormat($res[0]['XYJSRQ']))+86400;
        } else {
            $xyksrq = strtotime('-1 year')+86400;
            $xyjsrq = time();
        }
        $guzzle = new Client();
        $response = $guzzle->post('http://api.hatzjh.com/live/getmedinfo',[
            'query' => [
                'username' => 'haqhJJCX',
                'password' => 'JJCXMediator',
                '_time' => 1,
            ],
            'form_params' => [
                'data' => json_encode([
                    [
                        'begintime' => $xyksrq,
                        'endtime' => $xyjsrq,
                        'name' => $name,
                        'number' => $number
                    ]
                ])
            ]
        ]);
        
        $body = $response->getBody();
        $body = json_decode((string)$body,true);
        if(isset($body['code'])) {
            return [
                'code' => 500,
                'message' => '未找到相关记录'
            ];
        }
        $body = $body[0];
        if($body['code'] == 400){
            $re = [
                'code' => 500,
                'message' => '未参加线下视频培训'
            ];
        }elseif($body['code'] == 200){
            $hour = floor($body['total_time']/3600);  
            $minute = floor(($body['total_time']-3600 * $hour)/60);  
            $second = floor((($body['total_time']-3600 * $hour) - 60 * $minute) % 60);
            return [
                'code' => 200,
                'message' => '查询成功',
                'data' => [
                    'time' => $body['total_time'],
                    'format' => $hour."小时".$minute."分钟".$second."秒"
                ]
            ];
        }
        return [
            'code' => 500,
            'message' => '未找到相关记录'
        ];

    }

    /**
     * 格式化crm日期
     * @param $date
     * @return string
     */
    public function crmDateFormat($date)
    {
        $Y = substr($date,0,4);
        $m = substr($date,4,2);
        $d = substr($date,6,2);
        return $Y."-".$m."-".$d;
    }

    /**
     * 发送短信 单发
     * @param string $phone 手机号
     * @param string $content 内容
     * @param string $channel 通道 短信验证码使用 YZM
     * @param array $param 额外参数
     */
    public function sendSmsSingle($phone, $code, $channel = 'YZM', $param = []){
        $result = SMS::send($phone, $code, $param, $channel);
        $end = end($result);
        if(isset($end['status']) && 'success' == $end['status']){
            return true;
        } else {
            $gateway = $end['gateway'];
            $settingInfo = SysSmsSetting::where('unique_name', $gateway)->first();
            if($settingInfo) {
                $setting = json_decode($settingInfo->setting, true);
                $statusList = $setting['status'];
                if(isset($statusList[$end['code']])) {
                    return $statusList[$end['code']];
                } 
            } 
        }
        return '短信发送失败';
    }

    /**
     * 密码强度验证
     * @param $str
     * @return bool
     */
    public function isPWD($str)
    {
        $score = 0;
        $array = [
            "/[0-9]+/",
            "/[a-z]+/",
            "/[A-Z]+/",
            "/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/"
        ];
        if(strlen($str) >= 8 && strlen($str) <= 20){
            foreach($array as $key){
                if(preg_match($key,$str)){
                    $score ++;
                }
            }
        }
        if($score <= 3){
            return false;
        }else{
            return true;
        }
    }
}