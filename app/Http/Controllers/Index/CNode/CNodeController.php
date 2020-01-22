<?php


namespace App\Http\Controllers\Index\CNode;
require_once "simple_html_dom.php";

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseWebController;

use App\Models\Index\CNode\RpaAccountFlows;
use DB;
use simple_html_dom;

class CNodeController extends BaseWebController
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        return view('Index.CNode.index');
    }

    public function getCNode(Request $request)
    {
        $data = $this->get_params($request, ['mname', 'mnum', 'cname', 'cphone']);

        if (!isset($data['cname']) && !isset($data['cphone'])) {
            return $this->ajax_return(500, '请至少填写一个姓名或手机号！');
        }

        $array = ['Sunday', 'Saturday'];
        $date = date('H:i:s');
        if(in_array(getdate()['weekday'], $array) || ('17:30:00' < $date || $date < '08:30:00')){
            return $this->ajax_return(500, '请注意，查询时间为工作日的8:30 ~ 17:30！');
        }

//        if(!isset($data['mname']) || !isset($data['mnum'])){
//            return $this->ajax_return(500, '请填写您的姓名和工号！');
//        }

//        $this->validate($request, [
//            'mname' => 'required|string',
//            'mnum' => 'required|integer'
//        ]);

        //验证客户经理

//        $sql = "select id from TXCTC_YGXX where XM ='". htmlspecialchars(htmlentities(addslashes($data['mname']))) ."'and BH ='".htmlspecialchars(htmlentities(addslashes($data['mnum'])))."'";
//        $param = [
//            'type' => 'common',
//            'action' => 'getEveryBy',
//            'param' => [
//                'table' => 'CJLS',
//                'by' => $sql
//            ]
//        ];
//        $r = $this->getCrmData($param);
//
//        if(!$r){
//            return $this->ajax_return(500, '客户经理信息错误！');
//        }

        $condition = [];

        if (isset($data['cname'])) {
            $condition[] = ['name', '=', $data['cname']];
        }

        if (isset($data['cphone'])) {
            $condition[] = ['tel', '=', $data['cphone']];
        }

//        $condition[] = ['regtime', '=', date('Y-m-d')];

        $res = RpaAccountFlows::where($condition)->groupBy('id')->get(['tel', 'name', 'tzzsdx', 'qudao', 'belong', 'state', 'fhstate', 'regtime', 'url'])->toArray();

        if ($res) {
            $dom = self::getRealRecord($res[0]['url']);

            $html = new simple_html_dom();
            $html->load($dom['data']);
            $strDom = $html->find('table[id=openDetail] tbody tr');
            $realResult = [];
            $i = 0;
            foreach($strDom as $k => $v) {
                if($i >= 5)
                break;
                $tds = $v->children;
                $child = [];
                foreach($tds as $td) {
                    $child[] = $td->outertext;
                }
                $realResult['data'][] = [
                    'time' => $child[0],
                    'operation' => $child[1],
                    'desc' => $child[2]
                ];
                $i++;
            }

            $name = mb_substr($res[0]['name'], 0, 1). str_repeat('*', mb_strlen($res[0]['name']) - 1);
            $tel = str_repeat('*', 7).substr($res[0]['tel'], 7, 11);
            $realResult['title'] = "姓名：".$name.' 、手机号：'.$tel;
            $html->clear();
            unset($html);
            $html=null;
            return $this->ajax_return(200, 'success', $realResult);
        } else {
            return $this->ajax_return(500, '查询失败，暂无该客户数据！');
        }
        return $this->ajax_return(200, 'success', $res);
    }


    private function getRealRecord($url)
    {

        $res = DB::table('rpa_accesstokens')->where('username', 'YunkhCookie')->get(['token'])->toArray();
        if ($res) {
            $cookie = $res[0]->token;
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $headers[] = 'Referer: http://172.16.191.174:8082/futuaccount/index.htm?acceptAuditPublicFunctionProcessing=single';
            $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8';
            $headers[] = 'Accept-Encoding: gzip, deflate';
            $headers[] = 'Accept-Language: zh-CN,zh;q=0.9';
            $headers[] = 'Cache-Control: no-cache';
            $headers[] = 'Connection: keep-alive';
            $headers[] = 'Pragma: no-cache';
            $headers[] = 'Host: 172.16.191.174:8082';
            $headers[] = 'Upgrade-Insecure-Requests: 1';
            $headers[] = "Cookie:{$cookie}";
            $result = self::curlPost($url, $data = [], $headers);
            return $result;
        }

    }


    /**
     * 发送postq请求
     * @param $url
     * @param $data
     * @param array $headers
     * @param int $getCookie
     * @return array
     */
    public static function curlPost($url, $data, $headers = [], $getCookie = 0)
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)'); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 10); // 设置超时限制防止死循环
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
        if ($getCookie) {
            list($header, $body) = explode("\r\n\r\n", $result, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
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