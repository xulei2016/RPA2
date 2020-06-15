<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Func\rpa_customer_manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Mail\MdEmail;
use App\models\admin\base\SysMail;
use Illuminate\Support\Facades\Mail;

/**
 * 客户同步接口
 *
 * @author hsulay
 * @since 20200324
 *
 */
class SyncCustomerController extends BaseApiController
{

    public function syncCustomerByDay($day = '')
    {
    }

    /**
     * 同步CRM系统单客户信息
     * 
     */
    public function syncCrmCustomer(Request $request)
    {
        // return bcrypt('712311Wh');
        $zjzh = $request->zjzh;

        $crm_date = [
            'type' => 'customer',
            'action' => 'syncCustomerInfo',
            'param' => [
                'table' => 'KHXX',
                'zjzh' => $zjzh
            ]
        ];
        $res = $this->getCrmData($crm_date);
        return response()->json([
            'status' => 200,
            'msg' => $res,
        ]);
    }

    /**
     * 同步客户关系，若存在则仅同步一次
     * 若存在居间关系，则同步到回访列表
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function syncHistoryCustomer()
    {
        //初始日期 2020-01-16
        $start = '2020-01-16';

        $yesterday = date('Y-m-d', strtotime('-1 day', time()));

        //查询待处理数据集
        $conditions = [
            ['special', '=', ''],
            ['add_time', '>=', $start],
            ['add_time', '<', $yesterday],
            ['isSync', '=', '0']
        ];
        $arms = rpa_customer_manager::where($conditions)->get(['id', 'fundsNum', 'yybNum', 'yybName', 'jjrNum', 'jjrName', 'customerNum', 'customerManagerName']);

        //构造资金账号列表
        $armLists = [];
        $waitSyncList = [];
        $arms->map(function ($item) use (&$armLists, &$waitSyncList){
            $armLists[] = $item->fundsNum;
            $waitSyncList[$item->fundsNum] = $item;
        });

        //查询对应资金账号客户关系
        $armLists = implode(',', $armLists);
        $crm_date = [
            'type' => 'customer',
            'action' => 'getCustomerRelation',
            'param' => [
                'arms' => $armLists
            ]
        ];
        $offlineCustomers = $this->getCrmData($crm_date);

        //客户关系比较 -》 同步更新 -》标记
        if(!empty($offlineCustomers)){
            $syncDate = date('Y-m-d H:i:s');
            $waitVisitCustomerLists = [];
            foreach($offlineCustomers as $offlineCustomer){
                if(isset($waitSyncList[$offlineCustomer['ZJZH']])){
                    $data = [
                        'sync_yyb_name' => $offlineCustomer['YYB'],
                        'sync_yyb_num' => $offlineCustomer['YYBBH'],
                        'sync_jjr_name' => $offlineCustomer['JJR'],
                        'sync_jjr_num' => $offlineCustomer['JJRBH'],
                        'sync_jlr_name' => $offlineCustomer['YG'],
                        'sync_jlr_num' => $offlineCustomer['YGBH'],
                        'KHRQ' => $offlineCustomer['KHRQ'],
                        'sync_time' => $syncDate,
                        'isSync' => 1
                    ];
                    $res = rpa_customer_manager::where('fundsNum', $offlineCustomer['ZJZH'])->orderBy('add_time', 'desc')->firstOrFail();
                    $result = rpa_customer_manager::where('id', $res['id'])->update($data);
                    //非特殊客户，存在居间客户关系，加入到回访列表
                    if($offlineCustomer['JJRBH'] && !$res['special']){
                        $waitVisitCustomerLists[] = [
                            'customer_id' => $waitSyncList[$offlineCustomer['ZJZH']] -> id,
                            'created_at' => $syncDate
                        ];
                    }
                    if(!$result){
                        return response()->json([
                            'status' => 500,
                            'msg' => $offlineCustomer['ZJZH']."客户信息同步失败！",
                        ]);
                        break;
                    }
                }
            }
            $res = DB::table('rpa_revisit_customers')->insert($waitVisitCustomerLists);
            if($res){
                $msg = "更新完成！";
            }
        }

        $data = [
            'title' => '每日任务',
            'content' => $msg ?? "无可更新客户！【{$start}】",
            'tid' => 3
        ];
        $sysMail = SysMail::create($data);
        Mail::to('fatlay@foxmail.com')->send(new MdEmail($sysMail));

        return response()->json([
            'status' => 200,
            'msg' => $msg ?? "无可更新客户！",
        ]);
    }

    /**
     * 同步数据， 1、 同步线下客户 2、同步客户日期
     * 次日运行
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncOfflineCustomer(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day', strtotime($date)));

        //同步历史客户
        $crm_data = [
            'type' => 'customer',
            'action' => 'getCrmCustomerBetweenDays',
            'param' => [
                'start' => $yesterday,
                'end' => $date
            ]
        ];

        $crm_customers = $this->getCrmData($crm_data);

        $crm = [];
        $crm_zjzhs = [];
        foreach ($crm_customers as $customer) {
            $crm[] = [
                'KHRQ' => $customer['KHRQ'],
                'fundsNum' => $customer['ZJZH']
            ];
            $crm_zjzhs[] = $customer['ZJZH'];
            $result = rpa_customer_manager::where('fundsNum', $customer['ZJZH'])->update(['KHRQ' => $customer['KHRQ']]);
            if (true) {
                continue;
            } else {
                return response()->json([
                    'status' => 500,
                    'msg' => $customer['ZJZH'] . "客户信息开户日期同步失败！"
                ]);
            }
        }

        $condition = [
            ['add_time', '>=', $yesterday],
            ['add_time', '<', $date],
            ['special', '=', '']
        ];
        //本地客户列表
        $localCustomerLists = rpa_customer_manager::where($condition)->pluck('fundsNum')->toArray();

        $diffCustomers = array_diff($crm_zjzhs, $localCustomerLists);

        if (!empty($diffCustomers)) {
            $arms = implode(',', $diffCustomers);
            $crm_date = [
                'type' => 'customer',
                'action' => 'getCustomerAndYYB',
                'param' => [
                    'arms' => $arms
                ]
            ];

            $offlineCustomers = $this->getCrmData($crm_date);

            $data = [];
            foreach ($offlineCustomers as $offlineCustomer) {
                $data[] = [
                    'name' => $offlineCustomer['KHXM'],
                    'idCard' => '',
                    'customerNum' => '',
                    'fundsNum' => $offlineCustomer['ZJZH'],
                    'creater' => 'RPA',
                    'yybNum' => $offlineCustomer['YYB'],
                    'yybName' => $offlineCustomer['YYBNAME'],
                    'customerManagerName' => '',
                    'KHRQ' => $offlineCustomer['KHRQ'],
                    'special' => '',
                    'add_time' => "{$yesterday} 23:59:59",
                    'message' => '线下自同步',
                    'is_online' => 0
                ];
            }

            $result = DB::table('rpa_customer_managers')->insert($data);

            if ($result) {
                $msg = "success," . count($offlineCustomers) . "线下客户已同步！";
            }
        }
        $data = [
            'title' => '每日任务',
            'content' => $msg ?? "无可同步线下户！【{$date}】",
            'tid' => 3
        ];
        $sysMail = SysMail::create($data);
        Mail::to('fatlay@foxmail.com')->send(new MdEmail($sysMail));

        return response()->json([
            'status' => 200,
            'msg' => $msg ?? "无可同步线下户！",
        ]);
    }

    /**
     * syncAppointCustomer function
     *
     * @return void
     * @Description 同步指定客户信息
     */
    public function syncAppointCustomer(Request $request)
    {
        $zjzhs = $request->zjzhs;

        if(!empty($zjzhs)){
            $crm_date = [
                'type' => 'customer',
                'action' => 'getCustomerRelation',
                'param' => [
                    'arms' => $zjzhs
                ]
            ];
            $offlineCustomers = $this->getCrmData($crm_date);

            //客户关系比较 -》 同步更新 -》标记
            if(!empty($offlineCustomers)){
                $syncDate = date('Y-m-d H:i:s');
                $waitVisitCustomerLists = [];
                foreach($offlineCustomers as $offlineCustomer){
                    $data = [
                        'sync_yyb_name' => $offlineCustomer['YYB'],
                        'sync_yyb_num' => $offlineCustomer['YYBBH'],
                        'sync_jjr_name' => $offlineCustomer['JJR'],
                        'sync_jjr_num' => $offlineCustomer['JJRBH'],
                        'sync_jlr_name' => $offlineCustomer['YG'],
                        'sync_jlr_num' => $offlineCustomer['YGBH'],
                        'KHRQ' => $offlineCustomer['KHRQ'],
                        'sync_time' => $syncDate,
                        'isSync' => 1
                    ];
                    $conditions = [
                        ['fundsNum', '=', $offlineCustomer['ZJZH']],
                        ['special', '=', '']
                    ];
                    $res = rpa_customer_manager::where($conditions)->orderBy('add_time', 'desc')->firstOrFail();
                    $result = rpa_customer_manager::where('id', $res['id'])->update($data);
                    //非特殊客户，存在居间客户关系，加入到回访列表
                    if($offlineCustomer['JJRBH']){
                        $res = RpaRevisitCustomers::firstOrCreate([
                            'customer_id' => $res['id'],
                            // 'created_at' => $syncDate
                        ]);
                        if(!$res){
                            return response()->json([
                                'status' => 500,
                                'msg' => $offlineCustomer['ZJZH']."客户信息同步失败！",
                            ]);
                            break;
                        }
                    }
                }
                return response()->json([
                    'status' => 200,
                    'msg' => "success",
                ]);
            }else{
                return response()->json([
                    'status' => 500,
                    'msg' => "CRM数据查询失败，无可用关系数据！",
                ]);
            }
        }else{
            return response()->json([
                'status' => 500,
                'msg' => "无可更新用户！",
            ]);
        }
    }


}
