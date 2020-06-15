<?php

namespace App\Http\Controllers\Index\CNode;

use App\Models\Index\CNode\FuncAccountProgressQueryRecord;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\Base\BaseWebController;

use App\Models\Index\CNode\RpaAccountFlows;
use App\Libraries\Utils\simple_html_dom;
use DB;

/**
 * CNodeController class
 *
 * @Description 新开户客户开户进度查询功能
 * @author Hsu Lay
 * @version 1.1
 */
class CNodeController extends BaseWebController
{
    public function __construct()
    {
    }

    /**
     * 首页
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        return view('Index.CNode.index');
    }

    /**
     * 获取开户云系统html dom
     *
     * @param [type] $url
     * @return mixed
     * @Description
     */
    private function getRealRecord($url)
    {
        $res = DB::table('rpa_accesstokens')->where('username', 'YunkhCookie')->get(['token'])->toArray();
        $token = $res[0]->token;
        $guzzle = new Client();
        $response = $guzzle->post($url, [
            'timeout' => 0,
            'form_params' => [],
            'verify' => false,
            'headers' => [
                'User-Agent' => 'testing/1.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => $token
            ]
        ]);
        $body = $response->getBody();
        $htmlStr = (String)$body;
        return $htmlStr;

    }

    /**
     * 获取结果页面
     */
    public function getResultView(Request $request)
    {
        $data = $this->get_params($request, ['mname', 'mnum', 'cname', 'cphone']);
        if (!isset($data['cname']) && !isset($data['cphone'])) {
            return $this->ajax_return(500, '请至少填写一个姓名或手机号！');
        }

        $array = ['Sunday', 'Saturday'];
        $date = date('H:i:s');
        if (in_array(getdate()['weekday'], $array) || ('17:30:00' < $date || $date < '08:30:00')) {
            return $this->ajax_return(500, '请注意，查询时间为工作日的8:30 ~ 17:30！');
        }

        $condition = [];

        if (isset($data['cname'])) {
            $condition[] = ['name', '=', $data['cname']];
        }

        if (isset($data['cphone'])) {
            $condition[] = ['tel', '=', $data['cphone']];
        }

        $flows = RpaAccountFlows::where($condition)->groupBy('id')->get(['tel', 'name', 'tzzsdx', 'qudao', 'belong', 'state', 'fhstate', 'regtime', 'url'])->toArray();


        if ($flows) {
            $finalResult = [];
            foreach ($flows as $key => $flow) {
                try {
                    $dom = self::getRealRecord($flow['url']);
                    // echo $dom;die;
                    $html = new simple_html_dom();
                    $html->load($dom);
                    $strDom = $html->find('table[id=openDetail] tbody tr'); // 开户流程信息
                    $item = [];
                    $i = 0;
                    foreach ($strDom as $k => $v) {
                        if ($i >= 5)
                            break;
                        $tds = $v->children;
                        $child = [];
                        foreach ($tds as $td) {
                            $child[] = $td->outertext;
                        }
                        $item[] = [
                            'time' => $child[0],
                            'operation' => $child[1],
                            'desc' => $child[2]
                        ];
                        $i++;
                    }
                    $name = $flow['name']?mb_substr($flow['name'], 0, 1) . str_repeat('*', mb_strlen($flow['name']) - 1):'';
                    $tel = substr_replace($flow['tel'], "****", 3, 4);
                    $finalResult[] = [
                        'index' => 'index' . $key,
                        'name' => $name,
                        'phone' => $tel,
                        'list' => $item
                    ];

                    // 个人信息
                    $infoDom = $html->find('table[id=basicinfo] tr td');
                    $infoArray = [];
                    foreach ($infoDom as $k => $v) {
                        if ($k % 2 == 0) {
                            $infoArray[$v->innertext] = $infoDom[$k + 1]->plaintext;
                        }
                    }

                    // 获取第三方银行信息
                    $bankInfo = $html->find("div.thirdPartyBank td");
                    $bankArray = [];
                    foreach ($bankInfo as $k => $v) {
                        if ($k % 2 == 0) {
                            $bankArray[$v->innertext] = $bankInfo[$k + 1]->plaintext;
                        }
                    }
                    $add = [
                        'name' => $infoArray['姓名'],
                        'phone' => $flow['tel'],
                        'zjzh' => $bankArray['资金账户'],
                        'customer_manager_number' => $infoArray['客户经理工号'],
                        'mediator_number' => $infoArray['居间人编号'],
                        'yyb' => $infoArray['开户营业部'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                        'ip' => $request->getClientIp(),

                    ];
                    FuncAccountProgressQueryRecord::create($add);
                    $html->clear();
                    unset($html);
                    $html = null;
                } catch (\Exception $e) {

                }

            }
            $html = view('Index.CNode.table', ['list' => $finalResult]);
            return $this->ajax_return(200, 'success', response($html)->getContent());
        } else {
            return $this->ajax_return(500, '查询失败，暂无该客户数据！');
        }
        return $this->ajax_return(200, 'success', $res);
    }

}