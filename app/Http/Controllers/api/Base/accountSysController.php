<?php

namespace App\Http\Controllers\api\Base;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class accountSysController extends Controller
{
    protected $cookie = "";

    /**
     * 获取适当性数据
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSdx(Request $request)
    {
        $validatedData = $request->validate([
            'clientId' => 'required_without:idNo',
            'idKind' => 'required_without:clientId',
            'idNo' => 'required_without:clientId',
        ]);

        if(!$this->checkCookie()){
            $re = [
                'status' => 500,
                'msg' => '登录失败'
            ];
            return response()->json($re);
        }
        $url = config('accountSys.SC.url') . "/futuaccount/unionAcct/queryCustomerToajax.json";
        $data = [
            'clientId' => $request->clientId,
            'idKind' => $request->idKind,
            'idNo' => $request->idNo,
            'actionIn' => 0
        ];
        $headers = [
            $this->cookie
        ];
        $res = $this->curlPost($url,$data,$headers,1);
        if($res['code'] == 200){
            $content = json_decode($res['data']['content'],true);
            if(isset($content['errorNo'])){
                $re = [
                    'status' => 500,
                    'msg' => $content['errorInfo']
                ];
            }else{
                $re = [
                    'status' => 200,
                    'msg' => $content
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '查询出错'
            ];
        }

        return response()->json($re);
    }

    /**
     * 登录
     * @return bool
     */
    private function login()
    {
        $data = [
            'user_name' => config('accountSys.SC.account'),
            'password' => config('accountSys.SC.password'),
            'checkCode' => '',
            'to_url' => '',
            'macAddress' => ''
        ];
        $url = config('accountSys.SC.url') . "/futuaccount/login.htm";

        $headers = [];

        $res = $this->curlPost($url,$data,$headers,1);
        if($res['code'] == 200){
            if(strpos($res['data']['content'],'登陆出现错误') > -1){
                $return = false;
            }else{
                $this->cookie = "Cookie: ".$res['data']['cookie'];
                $return = true;
            }
        }else{
            $return = false;
        }
        return $return;
    }

    /**
     * 检查cookie是否存在
     * @return bool
     */
    private function checkCookie()
    {
        if(!$this->cookie){
            return $this->login();
        }else{
            return true;
        }
    }

    /**
     * 发送post请求
     * @param $url
     * @param $data
     * @param array $headers
     * @param int $getCookie
     * @return array
     */
    private function curlPost($url, $data, $headers = [], $getCookie = 0){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)'); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        if(is_array($data)) {
            $data = http_build_query($data);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 5); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HEADER, $getCookie);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            return [
                'code' => 500,
                'message' => curl_error($curl)
            ];
        }
        curl_close($curl); // 关闭CURL会话
        if($getCookie) {
            list($header, $body) = explode("\r\n\r\n", $result, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            if(isset($matches[1][0]) && isset($matches[1][1])){
                if(strpos($matches[1][0],"hm=\"")){
                    $info['cookie']  = $matches[1][0] .";". substr($matches[1][1], 1);
                }else{
                    $info['cookie']  = $matches[1][1] .";". substr($matches[1][0], 1);
                }
            }
            $info['content'] = $body;
            return [
                'code' => 200,
                'data' => $info
            ];
        } else {
            return [
                'code' => 200,
                'data' => $result
            ];
        }

    }
}
