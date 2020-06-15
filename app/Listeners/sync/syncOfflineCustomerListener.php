<?php

namespace App\Listeners\sync;

use App\Events\Sync\SyncOfflineCustomer;
use App\Http\Controllers\base\BaseAdminController;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Admin\SysAdminAlert;

use Illuminate\Support\Facades\Log;

class syncOfflineCustomerListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SyncOfflineCustomer $event
     * @return void
     */
    public function handle(SyncOfflineCustomer $event)
    {
        $data = $event->getData();

        $status = false;

        //识别上线同步还是线下同步 1线上 2线下
        switch ($event->getType()) {
            case 1:
                $status = self::sync($data);
                break;
            case 2:
                $status = true;
                // $status = self::syncOffline($data);
                break;
        }

        if(!$status){
            SysAdminAlert::create([
                'user_id' => 1,
                'title' => '失败警告',
                'content' => 'Sync同步事件触发失败警告！',
                'type' => 'warning'
            ]);
            Log::error('sync同步任务失败或者为空'.$event->getType(), $data);
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    private static function sync($data)
    {
        if(is_object($data) || is_array($data)){
            $id = is_object($data) ? $data->id : $data['id'];

            $record = [
                'customer_id' => $id,
            ];

            return RpaRevisitCustomers::create($record);
        }
        //调试 2020-03-06
        Log::info($record, $data);
        
        return false;
    }

    /**
     * @param array $data
     * @return bool
     */
    private static function syncOffline(array $data)
    {
        if (!empty($data)) {
            $res = '';
            foreach ($data as $v) {
                $array = [
                    'name' => $v['KHXM'],
                    'idCard' => $v['ZJBH'],
                    'customerNum' => '',
                    'fundsNum' => $v['ZJZH'],
                    'creater' => 'Offline_event',
                    'jjrNum' => '',
                    'jjrName' => '',
                    'yybName' => '',
                    'yybNum' => $v['YYB'],
                    'customerManagerName' => '',
                    'status' => 2,
                    'add_time' => date('Y-m-d H:i:s')
                ];

                $post_data = [
                    'type' => 'jjr',
                    'action' => 'get_customer_relation',
                    'param' => [
                        'yyb_number' => $v['YYB'],
                        'kid' => $v['ID']
                    ]
                ];
                $relationInfo = (new \App\Http\Controllers\base\BaseAdminController)->getCrmData($post_data);
                if($relationInfo){
                    $array['jjrNum'] = $relationInfo['jjr_number'];
                    $array['jjrName'] = $relationInfo['jjr_name'];
                    $array['yybName'] = $relationInfo['yyb_name'];
                    $array['customerNum'] = $relationInfo['jlr_number'];
                    $array['customerManagerName'] = $relationInfo['jlr_name'];

                    try{

                        $res = rpa_customer_manager::create($array);
    
                        return self::sync($res);

                    }catch(\Exception $e){
                        return $e->getMessage();
                    }
                }
            }
        }
        return false;
    }

}
