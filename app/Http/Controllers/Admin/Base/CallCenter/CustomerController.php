<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Events\CallCenterCustomerChangeEvent;
use App\Events\CallCenterCustomerEvent;
use App\Models\Admin\Base\CallCenter\SysCustomer;
use App\Models\Admin\Base\CallCenter\SysRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use GuzzleHttp\Client;

class CustomerController extends BaseController
{
    private $view_prefix = 'admin.base.callCenter.customer.';

    /**
     * 登录界面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function login(){
        return view($this->view_prefix.'login');
    }

    /**
     * 忘记密码
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forget(){
        return view($this->view_prefix.'forget');
    }

    /**
     * 聊天
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chat(){
        return view($this->view_prefix.'chat');
    }

    /**
     * 用户登出
     * @param Request $request
     * @return array
     */
    public function logout(Request $request)
    {
        $this->customer_id = $request->post('customer_id');
        $this->noticeOnlineManager('customer_remove');
        $this->leaveOnlineVisitors($this->customer_id);
        return $this->ajax_return(200, 'success');
    }

    /**
     * 登录
     * @param Request $request
     * @return array
     */
    public function doLogin(Request $request){
        $name = $request->post('name', null);
        $this->avatar = $request->post('avatar', '/callCenter/img/a.png');
        if(!$name) return $this->ajax_return(500, "缺少必要的参数姓名");
        $zjzh = $request->post('zjzh', null);
        $card = $request->post('card', null);
        $condition = [
            'name' => $name
        ];
        $by = [
            ['KHXM', '=', $name],
            ['KHZT', '=', 0]
        ];
        if($card) {
            $condition['card'] = $card;
            $by[] = ['ZJBM', '=', $card];
        }
        if($zjzh) {
            $condition['zjzh'] = $zjzh;
            $by[] = ['ZJZH', '=', $zjzh];
        }

        if(count($condition) < 2) return $this->ajax_return(500, "参数不足");

        //查询客户信息
        $customerData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => $by,
            ]
        ];
        $khxx = $this->getCrmData($customerData);
        if(!$khxx) {
            return $this->ajax_return(500, '您输入的姓名或账号有误,请重试');
        }
        $kh = $khxx[0];
        //查询交易编码
        $jys = [
            'NY' => '能源交易所',
            'DL' => '大连交易所',
            'ZZ' => '郑州交易所',
            'SQ' => '上海交易所',
            'ZJ' => '中国金融期货交易所',
        ];
        $jybmData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'JYBM',
                'by' => [
                    ['KHH', '=', $kh['KHH']]
                ],
            ]
        ];
        $jybms = $this->getCrmData($jybmData);

        $jybmList = [];
        foreach ($jybms as $v) {
            if($v['JYS'] == 'NY') {
                //获取持仓限额
                $limitData = [
                    'type' => 'common',
                    'action' => 'getEveryBy',
                    'param' => [
                        'table' => 'KHHYCCXE',
                        'by' => [
                            "select * from tfu_khhyccxe where ZJZH = '{$zjzh}'"
                        ],
                    ]
                ];
                $limits = $this->getCrmData($limitData);
                $auth = $this->getLimit($limits);
            } else {
                $auth =  $v['YWLX'];
            }

            $jybmList[] = [
                'jys' => trim($v['JYS']),
                'name' => $jys[trim($v['JYS'])],
                'auth' => $v['YWLX'],
                'jybm' => $v['JYBM']
            ];

        }
        //查询当日权益 field = BRJC  RQ  KHH  table = tfu_zjqkls
        $rightData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'ZJQKLS',
                'by' => [
                    ['KHH', '=', $kh['KHH']],
                    ['RQ', '=', date('Ymd')],
                ],
                'columns' => ['BRJC']
            ]
        ];
        $rights = $this->getCrmData($rightData);
        if(!$rights) {
            $right = '当日无权益';
        } else {
            $right = $rights[0]['BRJC'];
        }

        /**
         * 银期关系dcuser.TFU_YHZH_YQGL
         */
        $yqData = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TFU_YHZH_YQGL',
                'by' => "select * from TFU_YHZH_YQGL where ZJZH = '$zjzh'"
            ]
        ];
        $yqs = $this->getCrmData($yqData);
        if(!$yqs) {
            $yq = 0;
        } else {
            $yq = 2;
            foreach ($yqs as $v) {
                if($v['ZHZT'] == 0) {
                    $yq = 1;
                    break;
                }
            }
        }


        $data = [
            'client' => $this->getClientGroup($kh['KHFL_HS']),
            'ip' => $request->getClientIp(),
            'jybm' => json_encode($jybmList, JSON_UNESCAPED_UNICODE),
            'zjqy' => $right,
            'updated_at' => date('Y-m-d H:i:s'),
            'sxf' => $kh['KHSXF'],
            'bzj' => $kh['KHBZJ'],
            'yq' => $yq
        ];
        $visitor = SysCustomer::where($condition)->first();
        if($visitor) {
            SysCustomer::where('id', $visitor->id)->update($data);
            $this->customer_id = $visitor->id;
        } else {
            $data['gtsj'] = $kh['GTSJ'];
            $data['card']= $kh['ZJBH'];
            $data['khrq'] = $kh['KHRQ'];
            $data['name'] = $name;
            $data['zjzh'] = $kh['ZJZH'];
            $data['khh'] = $kh['KHH'];
            $data['created_at'] = date('Y-m-d H:i:s');
            $visitor = SysCustomer::create($data);
            $this->customer_id = $visitor->id;
        }

        //存入消息记录主表, 返回id
        $record = SysRecord::create(['customer_id' => $this->customer_id]);

        $this->record_id = $record->id;

        //加入在线用户列表
        $this->joinOnlineVisitors($visitor);

        // 通知所有客服
        $this->noticeOnlineManager('customer_add');

        // 返回订阅的频道
        $channel = $this->channel_prefix . $this->customer_id;

        $info = [
            'channel' => $channel,
            'event' => $this->event_name,
            'customer_id' => $this->customer_id,
            'href' => '/call_center/chat',
            'record_id' => $record->id
        ];
        return $this->ajax_return(200, "success", [
            'info' => $info,
            'config' => $this->getConfig(),
        ]);
    }

    /**
     * 加入在线客户列表(redis)
     * @param $visitor
     * @todo 获取用户详细信息并保存
     */
    private function joinOnlineVisitors($visitor)
    {
        $data = [
            'record_id' => $this->record_id,
            'customer_avatar' => $this->avatar,
            'customer_id' => $visitor->id,
            'customer_name' => $visitor->name,
            'status' => 1,
        ];
        Redis::hSet(self::ONLINE_CUSTOMER_LIST, $visitor->id, json_encode($data));
    }

    /**
     * 获取在线客户列表
     */
    public function getOnlineCustomerList(){
        $list = Redis::hGetAll(self::ONLINE_CUSTOMER_LIST);
        if(!$list) return $this->ajax_return(500,'没有在线用户');
        $result = [];
        foreach ($list as $k => $v) {
            $result[] = json_decode($v);
        }
        return $this->ajax_return(200, 'success', $result);
    }

    /**
     * 客户发送消息
     * @param Request $request
     */
    public function sendByCustomer(Request $request){
        $customer_id = $request->post('customer_id');
        $manager_id = $request->post('manager_id');
        $content = $request->post('content');
        $type = $request->post('type'); //类型
        $this->customer_id = $customer_id;
        $this->storeMessage($request->post());
        if(!$manager_id) {
            //客服id不存在, 自动回复
            $this->autoReply($type, $content);
        } else {
            $data = $this->messagePackaging($this->customer_id, $manager_id, 'customer','manager', $content, 'message');
            broadcast(new CallCenterCustomerEvent($data));
        }
    }

    public function getLimit($limits){
        if(!$limits) {
           return '156';
        }

        $yy = 0;
        $y20 = 0;
        $hasYY = false; // 是否有原油
        $hasY20 = false; // 是否有20号胶
        foreach($limits as $k => $v) {
            $pz = strtolower($v['HYPZ']); // 交易品种  sc 原油  nr 20号胶
            if($pz == 'sc') {
                $hasYY = true;
                if($v['HYDM'] != '!' || $v['XCSL'] > 0) {
                    $yy = 1;
                }
            } elseif($pz == 'nr'){
                $hasY20 = true;
                if($v['HYDM'] != '!' || $v['XCSL'] > 0) {
                    $y20 = 1;
                }
            }
        }
        if(!$hasYY) $yy = 1;
        if(!$hasY20) $y20 = 1;
        $res = '1';
        if($yy) $res .= '5';
        if($y20) $res .= '6';
        return $res;
    }

    /**
     * 根据id获取客户信息
     * @param Request $request
     * @return array
     */
    public function getById(Request $request){
        $result = SysCustomer::where('id', $request->id)->first();
        $info = $result->toArray();
        $jybms = json_decode($info['jybm'], true);
        $jybmList = [];
        foreach ($jybms as $k => $v) {
            if($v['jys'] == 'ZJ') {
                $jybmList[] = '金融';
            } elseif($v['jys'] == 'NY') {
                if(strpos($v['auth'], '5') > -1) {
                    $jybmList[] = '原油';
                }
                if(strpos($v['auth'], '6') > -1) {
                    $jybmList[] = '20号胶';
                }
            } elseif($v['jys'] == 'SQ') {
                if(strpos($v['auth'], '2') > -1) {
                    $jybmList[] = '上海期权';
                }
            } elseif ($v['jys'] == 'ZZ'){
                if(strpos($v['auth'], '2') > -1) {
                    $jybmList[] = '郑州期权';
                }
                if(strpos($v['auth'], '8') > -1) {
                    $jybmList[] = '郑州特定品种';
                }
            } elseif ($v['jys'] == 'DL'){
                if(strpos($v['auth'], '2') > -1) {
                    $jybmList[] = '大连期权';
                }
                if(strpos($v['auth'], '8') > -1) {
                    $jybmList[] = '大连特定品种';
                }
            }
        }
        if($info['yq'] == 0) {
            $yq = '未关联';
        } elseif($info['yq'] == 1) {
            $yq = '已关联';
        } else {
            $yq = '已解除';
        }
        $info['yq'] = $yq;
        $info['auth'] = implode('、', $jybmList);
        return $this->ajax_return(200, '查询成功', $info);
    }
}