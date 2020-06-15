<?php

namespace App\Http\Controllers\Index\ZT;

use App\Models\Admin\Api\Tmp\TmpYingqiChange;
use App\Models\Admin\Api\Tmp\TmpCustomer;
use App\Models\Admin\Api\Tmp\TmpOcrApiCount;
use App\Exceptions\zt\ValidateException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\App;

use GuzzleHttp\Client;

use App\Http\Controllers\api\BaseApiController;

class BalanceController extends BaseApiController
{
    protected $status = [
        0 => "已办理",
        1 => "办理中",
        2 => "办理失败",
        3 => "待办理"
    ];

    public function __construct()
    {
        $configs = config('baidu_api');
        $this->tokenUrl = $configs['token_url'];
        $this->bankCardOCRUrl = $configs['bankCard_OCR']['url'];
        $this->API_KEY = $configs['API_KEY'];
        $this->SECRET_KEY = $configs['SECRET_KEY'];
        $this->ocrCount = 20;
        $this->seatCtrl = APP::make(SeatController::class);
        $this->accounts = [];
    

        $this->middleware(function ($request, $next) {
            $this->user_id = $request->session()->get('tmp_customer_id');
            if ($this->user_id) {
                return $next($request);
            } else {
                $res =  ['status'=>200, 'code'=>10004, 'msg'=>'登录身份已失效，即将重新登录'];
                return response()->json($res);
            }
        });
    }
    
    /**
     * 提交数据
     * @return:
     */
    public function createYingqiChange(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'certificates_positive' => 'required',
            'certificates_negative' => 'required',
            'cards' => 'required',
            'type' => 'required'
        ]);

       
        //是否允许办理
        try {
            $allow = $this->allowCommit($request->cards, $request->type);
        } catch (ValidateException $e) {
            return $this->ajax_return(500, $e->getMessage());
        }
        
        if ($allow) {
            //保存证件正反面照片
            $fundsNum = TmpCustomer::where('id', $this->user_id)->first()->fundsNum;
            $certificates_positive = $this->seatCtrl->savePicture($request->certificates_positive, "/app/rpa/YQ/".$fundsNum."/");
            $certificates_negative = $this->seatCtrl->savePicture($request->certificates_negative, "/app/rpa/YQ/".$fundsNum."/");
            
            foreach ($request->cards as $card) {
                $bankCardImage = $this->seatCtrl->savePicture($card['bankCard'], "/app/rpa/YQ/".$fundsNum."/");
                $orderNum = $this->createOrderNumber();
                $oldAccount = "";
                //变更
                if ($request->type == 2) {
                    $oldAccount = $this->getTrueBankCardNum($request->oldAccount);
                }
                $data = [
                    'user_id' => $this->user_id,
                    'certificates_positive' => $certificates_positive,
                    'certificates_negative' => $certificates_negative,
                    'bank_card_image' => $bankCardImage,
                    'bank_card_num' => $card['bankCardNum'],
                    'opening_bank' => $card['openingBank'],
                    'bank_name' => $card['bankName'],
                    'old_account' => $oldAccount,
                    'old_bank_name' => $request->oldBankName,
                    'type' => $request->type,
                    'order_num' => $orderNum,
                    'status' => 3
                ];
                TmpYingqiChange::create($data);
            }
            try {
                $this->seatCtrl->createMessage('结算账户关联', '<p>有新的业务申请</p>');
            } catch (\Exception $e) {
                // do nothing
            }
            
            $res = ['status'=>200,'data'=> '提交成功'];
            return response()->json($res);
        }
    }

    /**
     * 获取真实银行卡号
     * @param string $signatureStr 加密的字符串
     * @return:
     */
    public function getTrueBankCardNum($signatureStr)
    {
        foreach ($this->accounts as $account) {
            if ($this->bankCardSignature($account['YHZH']) == $signatureStr) {
                return $account['YHZH'];
            }
        }
    }


    /**
     * 生成一个单号
     * @return:
     */
    public function createOrderNumber()
    {
        $order_no = date('Ym').$this->user_id.substr(time(), 3).rand(1000, 9999);

        return $order_no;
    }

    /**
     * 是否允许提交
     * @param array $cards 银行卡列表
     * @param int $type 类型(1新增 2变更)
     * @return:
     */
    //是否允许提交
    public function allowCommit($cards, $type)
    {
        $fundsNum = TmpCustomer::where('id', $this->user_id)->first()->fundsNum;
        $accounts = $this->getUserAccountList($fundsNum);
        $this->accounts = $accounts;
        //是新增
        if ($type == 1) {
            foreach ($cards as $card) {
                $this->validateAdd($accounts, $card);
            }
            $res = true;
        } else {
            $this->validateUpdate($accounts, $cards[0]);
        }

        return true;
    }

    /**
     * 能否添加  不能有相同的银行类型和卡号
     * @param array $accounts 已经存在的结算账户
     * @param array $card 要添加的银行卡
     * @return:
     */
    public function validateAdd($accounts, $card)
    {
        foreach ($accounts as $account) {
            if ($account['name'] == $card['openingBank']) {
                throw new ValidateException($card['openingBank'].'存在结算账户，不允许添加');
            }

            if ($account['YHZH'] == $card['bankCardNum']) {
                throw new ValidateException("该卡号已绑定过银期关系");
            }
        }

        return true;
    }

    /**
     * 能否变更 不可与原卡号一致
     * @param array $accounts 已经存在的结算账户
     * @param array $card 要变更的银行卡
     * @return:
     */
    public function validateUpdate($accounts, $card)
    {
        foreach ($accounts as $account) {
            if ($account['YHZH'] == $card['bankCardNum']) {
                throw new ValidateException("变更卡号与原卡号一致");
            } else {
                return true;
            }
        }

        throw new ValidateException("无此银行的结算账户");
    }

    /**
     * 识别银行卡 base64
     * @param string base64字符串
     * @return: 卡号 银行
     */
    public function bankCardOcrBase64(Request $request)
    {
        //一天调用20次
        if ($this->validateCall()) {
            try {
                $bankCard = $this->doOcr($request->imageBase64);
            } catch (\Exception $e) {
                return $this->ajax_return(500, "识别失败，请手动填写");
            }
            
            $cards = [['bankCardNum'=>$bankCard['account'], 'openingBank'=>$bankCard['name']]];
            $type = $request->type == 'add' ? 1 : 2;
            try {
                if ($this->allowCommit($cards, $type)) {
                    $data = ['status'=>200, 'data'=>$bankCard];
                }
            } catch (ValidateException $e) {
                return $this->ajax_return(500, $e->getMessage());
            }
        } else {
            $data = ['status'=>200, 'code'=>10002, 'msg'=>'今日识别已达一定次数，不可再调用'];
        }
        
        return response()->json($data);
    }

    /**
     * 验证能否调用识别接口 一天20次
     * @return:
     */
    public function validateCall()
    {
        $today = date("Y-m-d");
        $obj = TmpOcrApiCount::where('user_id', $this->user_id)->where('date', $today)->first();
        if ($obj) {
            if ($obj->count < $this->ocrCount) {
                $obj->count++;
                $obj->save();
                return true;
            } else {
                return false;
            }
        } else {
            $data = ['user_id'=>$this->user_id, 'date'=>$today, 'count'=>1];
            TmpOcrApiCount::create($data);
            return true;
        }
    }


    /**
     * 调用OCR识别 返回卡号和银行名称
     * @param string $base64 base64字符串
     * @return:
     */
    private function doOcr($base64)
    {
        $base64 = explode(',', $base64);
        $image = $base64[1];

        $guzzle = new Client([
            'verify' => false
        ]);
        $token = $this->getToken();
        $url = $this->bankCardOCRUrl."?access_token=".$token;
        $response = $guzzle->request('POST', $url, [
            'form_params' => [
                'image' => $image,
                'detect_direction' => config('baidu_api.bankCard_OCR.detect_direction')
            ]
        ]);
        $body = $response->getBody();
        $body = json_decode((string)($body), true);

        $res = ['account'=>'', 'name'=>''];
        if (isset($body['result'])) {
            $res = ['account' => str_replace(" ", '', $body['result']['bank_card_number']), 'name'=>$body['result']['bank_name']];
        }

        return $res;
    }

    /**
     * 获取调用银行卡识别接口百度的access_token
     * @return:
     */
    public function getToken()
    {
        $key = 'baidu_ocr_token';
        if (Cache::has($key)) {
            $token = Cache::get($key);
        } else {
            $token = $this->createToken();
            Cache::put($key, $token, 3600*24*3);
        }
       
        return $token;
    }

    /**
     * 生成调用接口token
     */
    public function createToken()
    {
        $clientId = $this->API_KEY;
        $clientSecret = $this->SECRET_KEY;
        $url = $this->tokenUrl."?grant_type=client_credentials&client_id=".$clientId."&client_secret=".$clientSecret."&";
        $result = file_get_contents($url);
        $token = "";
        if (isset($result)) {
            $result = json_decode($result, true);
            $token = $result['access_token'];
        }
        return $token;
    }

    /**
     * 获取银行列表
     */
    public function getBankList()
    {
        $param = [
            'type' => 'customer',
            'action' => 'getBankList',
            'param' => [
                'table' => 'TKHXX'
            ]
        ];
        $list = $this->getCrmData($param);
        $data = [];
        foreach ($list as $key => $l) {
            $data[] = ['text'=>$l, 'disabled'=>false];
        }
        $res = ['status'=>200, 'data' => $data];

        return response()->json($res);
    }

    /**
     * 获取用户已经存在的结算账户关系
     */
    public function getMyAccountList()
    {
        $fundsNum = TmpCustomer::where('id', $this->user_id)->first()->fundsNum;
        $list = $this->getUserAccountList($fundsNum);
        $data = $this->formatData($list);

        $res = ['status'=>200, 'data' => $data];
        return response()->json($res);
    }

    /**
     * CRM获取一个用户的结算账户列表
     * @param string $fundsNum 资金账户
     * @return:
     */
    public function getUserAccountList($fundsNum)
    {
        $param = [
            'type' => 'customer',
            'action' => 'getBankRelation',
            'param' => [
                'table' => 'TKHXX',
                'zjzh' => $fundsNum,
            ]
        ];
        $list = $this->getCrmData($param);
        
        return $list;
    }

    /**
     * 用户结算账户数据格式化 银行卡加* 银行卡md5加密
     * @param array $data 结算账户列表
     * @return:
     */
    public function formatData($data)
    {
        $res = [];
        foreach ($data as $d) {
            $account = $this->bankCardNumStar($d['YHZH']);
            $bankCardSignature = $this->bankCardSignature($d['YHZH']);
            $res[] = ['openingBank' => $d['name'], 'bankCardNum' => $account, 'bankCardSignature' => $bankCardSignature];
        }

        return $res;
    }

    /**
     * 给一张银行卡加密
     * @param string $account 卡号
     * @return:
     */
    public function bankCardSignature($account)
    {
        $str = "abced";
        return md5($account.$str);
    }

    /**
     * 给一张银行卡加****
     * @param string $account 卡号
     * @return:
     */
    public function bankCardNumStar($account)
    {
        $str = substr($account, 4, 8);
        $res = str_replace($str, '********', $account);
        return $res;
    }


    /**
     * 获取用户结算账户新增、变更记录
     * @return:
     */
    public function yingqiChangeDataList()
    {
        $res = TmpYingqiChange::where('user_id', $this->user_id)
        ->orderBy('created_at', 'desc')->get();
        foreach ($res as $k=>$v) {
            $res[$k]['bank_card_num'] = $this->bankCardNumStar($v['bank_card_num']);
            $res[$k]['old_account'] = $this->bankCardNumStar($v['old_account']);
        }
        $data = ['status'=>200, 'data'=>$res];
        
        return response()->json($data);
    }
}
