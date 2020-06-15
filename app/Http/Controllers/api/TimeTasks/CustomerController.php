<?php
/**
 * Created by PhpStorm.
 * User: cyx
 * Date: 2020/4/10
 * Time: 13:21
 */

namespace App\Http\Controllers\api\TimeTasks;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\rpa_timetasks;
use App\Models\Index\CNode\RpaAccountFlows;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Libraries\Utils\simple_html_dom;
use PHPExcel;
use PHPExcel_IOFactory;

class CustomerController extends BaseApiController
{

    public $yybList; // 营业部

    public function getL(){
        $xlsPath = __DIR__."/222.xls";
        $xlsReader = PHPExcel_IOFactory::createReader("Excel5");
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($xlsPath);
        $list = $Sheets->getSheet(0)->toArray();
        return $list;
    }

    public function test(){

        //die("1111");
        $phone = "15221934551";
        $res = RpaAccountFlows::where('tel', $phone)
            ->get(['tel', 'name', 'url'])->first();

        if($res) { // 线上客户 从云开户系统获取用户信息
            $url = $res->url;
            $res = DB::table('rpa_accesstokens')
                ->where('username', 'YunkhCookie')
                ->get(['token'])->toArray();
            $token = $res['0']->token;
            $guzzle = new Client();
            $response = $guzzle->post($url,[
                'timeout' => 0,
                'form_params' => [],
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'testing/1.0',
                    'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'      => $token
                ]
            ]);
            $body = $response->getBody();
            $htmlStr = (String)$body;
            // echo $htmlStr;die;
            $result = $this->parsingHtmlDom($htmlStr);
            var_dump($result);die;
            $html = new simple_html_dom();
            $html->load($htmlStr);
            $detailDom = $html->find('table[id=openDetail] tr td.OPDESCCont');
            $result = [];
            foreach($detailDom as $k => $v) {
                // var_dump($v->innertext);die;
                $result[] = $v->innertext;
                // if($k%2 == 0) {
                //     if(isset($detailDom[$k+1])) {
                //         $result[$v->innertext] = $detailDom[$k+1]->plaintext;
                //     }
                // }
            }
            var_dump($result);


        }

    }

    /**
     * 补全开户客户列表信息
     * @param Request $request
     */
    public function completeOpenCustomer(Request $request){
        $crmSameDayCustomerList = $this->getCrmSameDayCustomerList();
        $crmList = []; // crm 信息列表
        $crmArray = []; // crm 资金账号列表
        foreach($crmSameDayCustomerList as $v) {
            $crmArray[] = $v['ZJZH'];
            $crmList[$v['ZJZH']] = [
                'khh' => $v['KHH'],
                'zjzh' => $v['ZJZH'],
                'name' => $v['KHXM'],
                'idCard' => $v['ZJBH'],
                'phone' => $v['GTSJ'],
                'yyb' => $v['YYB']
            ];
        }
        $rpaArray = $this->getRpaSameDayCustomerList();
        sort($crmArray);
        sort($rpaArray);
        $diff = array_diff($crmArray, $rpaArray);
        foreach($diff as $v) {
            $detail = $crmList[$v];
            try {
                $this->perfectCustomer($detail);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 500,
                    'msg' => $e->getMessage()
                ]);
            }
        }
        $re = [
            'status' => 200,
            'msg' => '执行任务成功！客户数量'.count($diff)
        ];
        return response()->json($re);
    }

    //******************内部方法*********************//

    /**
     * 完善客户信息
     * @param $crmData
     */
    private function perfectCustomer($crmData){
        $phone = $crmData['phone'];
        $zjzh = $crmData['zjzh'];
        $res = RpaAccountFlows::where('tel', $phone)
            ->get(['tel', 'name', 'url'])->first();

        if($res) { // 线上客户 从云开户系统获取用户信息
            $url = $res->url;
            $res = DB::table('rpa_accesstokens')
                ->where('username', 'YunkhCookie')
                ->get(['token'])->toArray();
            $token = $res['0']->token;
            $guzzle = new Client();
            $response = $guzzle->post($url,[
                'timeout' => 0,
                'form_params' => [],
                'verify' => false,
                'headers' => [
                    'User-Agent' => 'testing/1.0',
                    'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Cookie'      => $token
                ]
            ]);
            $body = $response->getBody();
            $htmlStr = (String)$body;
            $result = $this->parsingHtmlDom($htmlStr);
            if($result['isOpen']) { // 开户云是否开户成功
                $this->saveCustomer($result, $crmData);
            } else { // 不成功 线下客户
                $add = [
                    'name' => $crmData['name'],
                    'idCard' => $crmData['idCard'],
                    'fundsNum' => $zjzh,
                    'creater' => 'RPA',
                    'KHRQ' => date('Y-m-d'),
                    'add_time' => date('Y-m-d H:i:s'),
                    'is_online' => 0, // 线下
                    'is_script' => 1, // 脚本
                    'message' => '自动维护',
                ];
                rpa_customer_manager::create($add);
            }
        } else { // 线下开户直接存储
            $add = [
                'name' => $crmData['name'],
                'idCard' => $crmData['idCard'],
                'fundsNum' => $zjzh,
                'creater' => 'RPA',
                'KHRQ' => date('Y-m-d'),
                'add_time' => date('Y-m-d H:i:s'),
                'is_online' => 0, // 线下
                'is_script' => 1, // 脚本
                'message' => '自动维护',
            ];
            rpa_customer_manager::create($add);
        }

    }

    /**
     * 解析html dom
     * @param $htmlStr
     * @return mixed
     */
    private function parsingHtmlDom($htmlStr){
        $html = new simple_html_dom();
        $html->load($htmlStr);
        $infoDom = $html->find('table[id=basicinfo] tr td');
        $result = [];
        foreach($infoDom as $k => $v) {
            if($k%2 == 0) {
                $result[$v->innertext] = $infoDom[$k+1]->plaintext;
            }
        }
        if(empty($result)) return false;

        // 判断客户类型 OPDESCCont
        $detailDom = $html->find('table[id=openDetail] tr td.OPDESCCont');
        $isOpen = 0;
        $type = 0; // 0 未知 1 新开户 2 激活
        foreach($detailDom as $k => $v) {
            $innterText = trim($v->innertext);

            //	提交柜台预开户成功
            if('提交柜台预开户成功' == $innterText) {
                $isOpen = 1;
            }
            if('开户申请复核通过' == $innterText) {
                $type = 1;
            } elseif('开户申请强制复核通过' == $innterText) {
                $type = 2;
            }
        }

        $operator = 'RPA';
        // 获取操作人 OPERCont
        // $openDom = $html->find('table[id=openDetail] tr td.OPERCont');
        // $customerName = $result['姓名'];
        // foreach($openDom as $k => $v) {
        //     $innterText = trim($v->innertext);
        //     if($innterText != '' && $innterText != $customerName) {
        //         $operator = $innterText;
        //         break;
        //     }
        // }
        $result['操作人'] = $operator;
        $result['type'] = $type;
        $result['isOpen'] = $isOpen;
        return $result;
    }

    /**
     * 保存客户信息
     * @param $data
     */
    private function saveCustomer($data, $crmData) {
        $add = [
            'name' => $data['姓名'],
            'idCard' => $data['身份证'],
            'customerNum' => $data['客户经理工号'],
            'customerManagerName' => '',
            'fundsNum' => $crmData['zjzh'],
            'creater' => $data['操作人'],
            'jjrNum' => $data['居间人编号'],
            'jjrName' => '',
            'yybName' => $data['开户营业部'],
            'yybNum' => $crmData['yyb'],
            'KHRQ' => date('Y-m-d'),
            'add_time' => date('Y-m-d H:i:s'),
            'message' => '自动维护',
            'is_online' => 1, // 线上
            'is_script' => 1, // 脚本
        ];
        $expirationDate = $data['身份证有效期限'];
        $expirationDateList = explode('-', $expirationDate);
        $add['sfz_date_begin'] = trim($expirationDateList[0]??'');
        $add['sfz_date_end'] = trim($expirationDateList[1]??'');
        //查询客户信息
        $params = [
            'type' => 'jjr',
            'action' => 'getNameByNumber',
            'param' => [
                'table' => 'TKHXX',
                'number' => '',
                'type' => ''
            ]
        ];

        if($add['customerNum']) { // 客户经理工号
            $params['param']['number'] = $add['customerNum'];
            $params['param']['type'] = 'customerManager';
            $result = $this->getCrmData($params);
            if($result) $add['customerManagerName'] = $result;
        }

        if($add['jjrNum']) {
            $params['param']['number'] = $add['jjrNum'];
            $params['param']['type'] = 'mediator';
            $result = $this->getCrmData($params);
            if($result) $add['jjrName'] = $result;
        }

        if(2 == $data['type']) { // 特殊户
            $add['special'] = '1';
        }
        if(1 == $data['type']) { // 线上新开户  需要发送任务
            $add['jjr'] = $add['jjrNum'];
            $this->sendTask($add);
        } else {
            $customer = rpa_customer_manager::create($add);
        }

    }

    /**
     * 发送任务
     */
    private function sendTask($data)
    {
        $guzzle = new Client(['verify'=>false]);
        $host = "http://".$_SERVER['HTTP_HOST'];
        $token = $this->access_token($host);
        $response = $guzzle->post($host.'/api/v2/sync_data',[
            'headers'=>[
                'Accept' => 'application/json',
                'Authorization' => $token
            ],
            'form_params' => $data
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
    }

    /**
     * 获取rpa当天开户列表
     */
    private function getRpaSameDayCustomerList(){
        $currentDay = date('Y-m-d');
        $rpaArray = rpa_customer_manager::where([
            ['KHRQ', '=', $currentDay]
        ])->pluck('fundsNum')->toArray();
        return $rpaArray;
    }

    /**
     * 获取crm当天开户列表
     */
    private function getCrmSameDayCustomerList(){
        $date = date('Ymd');
        $by = [
            ['KHZT', '=', 0], // 状态 0 表示正常
            ['KHRQ', '=', $date]
        ];
        //查询客户信息
        $params = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $by,
            ]
        ];

        $list = $this->getCrmData($params);
        return $list;
    }

    /**
     * 获取营业部 默认返回id => name
     * @param string $type
     * @return array
     */
    private function getYyb($type = 'front')
    {
        //查询营业部
        $data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JYBM',
                'by' => 'select ID,NAME from LBORGANIZATION'
            ]
        ];
        $list = $this->getCrmData($data);
        $newList = [];
        foreach($list as $v) {
            $newList[$v['ID']] = $v['NAME'];
        }
        if($type == 'back') {
            $newList = array_flip($newList);
        }
        $this->yybList = $newList;
        return $newList;
    }
}