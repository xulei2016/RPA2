<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use App\Mail\MdEmail;
use App\Models\Admin\Api\Trade\RpaHadmyVersion;
use App\Models\Admin\Api\Trade\RpaTradeLoginRecord;
use App\Models\Admin\Api\Trade\RpaTradeVersion;
use App\models\admin\base\SysMail;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\Api\Trade\RpaPobo5Code;
use Illuminate\Support\Facades\Mail;


class TradeApiController extends BaseApiController
{
    /**
     * 获取交易流水
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_jyls(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'khh' => 'required'
        ]);

        $zjzh = $this->decrypt($request->khh,"H@!qh2019dmy==,.");
        //根据资金账号查找客户号
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => [
                    ['ZJZH','=',$zjzh]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $khh = $result[0]['KHH'];
            //根据资金账号查询客户交易流水
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'CJLS',
                    'by' => [
                        ['KHH','=',$khh],
                        //['KHH','=','110005159'],
                        ['CJRQ','>',date('Ymd',strtotime('-1 year'))],
                    ],
                    'order' => [
                        ['JYRQ DESC']
                    ],
                    'limit' => 10000,
                    'columns' => ['HYPZ','CJRQ','CJSJ','HYDM','CJSL','MSDW','CJJG','WTLB','KPBZ']
                ]
            ];
            $result = $this->getCrmData($post_data);
            if($result){
                $len = strlen($zjzh);
                $re = [
                    'status' => 200,
                    'zjzh' => substr_replace($zjzh,str_repeat("*",(int)($len/2)),(int)($len/4),(int)($len/2)),
                    'msg' => $result
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '该资金账号未找到'
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => "该资金账号未找到!"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取交易日
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_jyr(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //获取交易日
        $sql = "select INIT_DATE from dcuser.tfu_tjyr_hs where EXCHANGE_TYPE = 'F1' ORDER BY INIT_DATE ASC";
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => $sql,
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $arr = [];
            foreach($result as $v){
                array_push($arr,$v['INIT_DATE']);
            }
            $re = [
                'status' => 200,
                'msg' => $arr
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '查询出错'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取码表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_code_table(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        $result = RpaPobo5Code::where('futuKind','<>','')->whereNotNull('futuKind')->get(['innerCode','futuShortName','futuName','futuKind']);
        if($result){
            $re = [
                'status' => 200,
                'msg' => $result
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '查询出错'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取指针地址
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_trade_version(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'FileMd5' => 'required'
        ]);

        $res = RpaTradeVersion::where('FileMd5',$request->FileMd5)->first();
        if($res){
            $return = [
                'status' => 200,
                'msg' => [
                    'PointerAddress' => $res['PointerAddress'],
                    'ByteSize' => $res['ByteSize']
                ]
            ];
        }else{
            $data = [
                'FileMd5'=>$request->FileMd5,
                'Version'=>$request->version,
            ];
            RpaTradeVersion::create($data);

            //邮件提示
            $data = [
                'title' => '账户系统接口错误报告',
                'content' => $request->FileMd5."的信息未找到!",
                'tid' => '2'
            ];
            $sysmail = SysMail::create($data);
            $to = 'youqi6588@126.com';
            Mail::to($to)->send(new MdEmail($sysmail));

            $return = [
                'status' => 500,
                'msg' => ''
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 登录记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginRecord(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'type' => 'required|in:start,end'
        ]);
        if($request->type == 'start'){
            $addr = $this->getAddress($request->getClientIp());
            $add = [
                'zjzh' => $this->decrypt($request->zjzh,"H@!qh2019dmy==,."),
                'tzjh_account' => $request->tzjh_account,
                'ip' => $request->ip,
                'ip2' => $request->getClientIp(),
                'province' => $addr['province'],
                'city' => $addr['city'],
                'mac' => $request->mac,
                'version' => $request->version,
                'start_time' => time(),
                'end_time' => time() + 1,
                'count_time' => 1
            ];
            $record = RpaTradeLoginRecord::create($add);
            $return = [
                'status' => 200,
                'msg' => $record->id
            ];
        }else{
            if($request->id){
                $record = RpaTradeLoginRecord::where('id',$request->id)->first();
                if($record){
                    $update = [
                        'end_time'=>time(),
                        'count_time' => time() - $record->start_time
                    ];
                    RpaTradeLoginRecord::where('id',$request->id)->update($update);
                    $return = [
                        'status' => 200,
                        'msg' => '更新成功'
                    ];
                }else{
                    $return = [
                        'status' => 500,
                        'msg' => '无效参数'
                    ];
                }
            }else{
                $return = [
                    'status' => 500,
                    'msg' => '参数错误'
                ];
            }
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *         \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function update_version(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'type' => 'required|in:1,2'
        ]);
        //根据版本号查询是否启用
        $ver = RpaHadmyVersion::where('version',$request->version)->first();
        // 获取最新版本
        $hadmyversion = RpaHadmyVersion::orderBy('version', 'desc')->first();
        if($request->type == 1){
            $re = [
                'status' => 200,
                'msg' => [
                    'new_version' => $hadmyversion->version,
                    'old_use' => $ver->status ?? 0
                ]
            ];
            //api日志
            $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

            return response()->json($re);
        }else{
            //下载

            //api日志
            $this->apiLog(__FUNCTION__,$request,'',$request->getClientIp());
            return response()->download($hadmyversion->url);
        }
    }

    /**
     * 解密程序
     */
    private function decrypt($data, $key)
    {
        $key = md5($key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char=$str='';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
     * 根据ip获取地址
     * @param $ip
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getAddress($ip){
        $guzzle = new Client();
        $url = config('baidu_api.ip_location.url');
        $ak = config('baidu_api.ak');
        $response = $guzzle->request('POST',$url,[
            'form_params' => [
                'ip' => $ip,
                'ak' => $ak
            ]
        ]);

        $body = $response->getBody();
        $body = json_decode((string)($body),true);
        if($body['status'] == 0){
            return [
                'province' => $body['content']['address_detail']['province'],
                'city' => $body['content']['address_detail']['city']
            ];
        }else{
            return [
                'province' => '',
                'city' => ''
            ];
        }
    }

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function login(Request $request)
    // {
    //     //ip检测
    //     $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
    //     if($res !== true){
    //         return response()->json($res);
    //     }

    //     //表单验证
    //     $validatedData = $request->validate([
    //         'user' => 'required',
    //         'password' => 'required',
    //         'mac' => 'required'

    //     ]);

    //     $user = auto_check::where(['user',$request->user])->first();

    //     if($user){
    //         //mac校验
    //         if($user->type == 1 && !strstr($user->mac, $request->mac)){
    //             $re = [
    //                 'status' => 500,
    //                 'msg' => '无权登录'
    //             ];
    //         }else{
    //             //登录验证
    //             if($request->password == $user->password){
    //                 //时间验证（2019-06-04）
    //                 $time = time();
    //                 if($user->expires && ($time > strtotime($user->expires))){
    //                     $re = [
    //                         'status' => 500,
    //                         'msg' => '账号过期'
    //                     ];
    //                 }else{
    //                     //登录成功
    //                     $data = [
    //                         'uid' => $user->id,
    //                         'name' => $user->name,
    //                         'mac' => $request->mac,
    //                         'ip' => $request->getClientIp()
    //                     ];
    //                     auto_check_record::create($data);

    //                     $re = [
    //                         'status' => 200,
    //                         'msg' => '登录成功'
    //                     ];
    //                 }
    //             }else{
    //                 $re = [
    //                     'status' => 500,
    //                     'msg' => '密码错误'
    //                 ];
    //             }
    //         }

    //     }else{
    //         $re = [
    //             'status' => 500,
    //             'msg' => '该用户名不存在！'
    //         ];
    //     }

    //     //api日志
    //     $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

    //     return response()->json($re);
    // }

    // public function record(Request $request)
    // {
    //     //ip检测
    //     $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
    //     if($res !== true){
    //         return response()->json($res);
    //     }

    //     //表单验证
    //     $validatedData = $request->validate([
    //         'user' => 'required',
    //         'info' => 'required'
    //     ]);

    //     $user = auto_check::where(['user',$request->user])->first();

    //     if($user){
    //         $data = [
    //             'uid' => $user->id,
    //             'name' => $request->name,
    //             'ip' => $request->getClientIp(),
    //             'info' => $request->info
    //         ];

    //         auto_record_info::create($data);

    //         $re = [
    //             'status' => 200,
    //             'msg' => '操作成功！'
    //         ];
    //     }else{
    //         $re = [
    //             'status' => 500,
    //             'msg' => '该用户不存在！'
    //         ];
    //     }

    //     //api日志
    //     $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

    //     return response()->json($re);
    // }
}
