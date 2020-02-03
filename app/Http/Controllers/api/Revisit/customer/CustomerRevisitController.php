<?php

namespace App\Http\Controllers\api\Revisit\customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomerRecords as Records;
use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Base\Sys\SysPhoneTable;

class CustomerRevisitController extends BaseApiController
{
    /**
     * 用户树状图
     *
     * @param Request $request
     * @return array
     */
    public function getRevisitList(Request $request)
    {
        $user = $request->user('api');
        $dept_id = $user->dept_id;

        if(!$dept_id){
            return $this->ajax_return(500, '暂无数据权限');
        }

        //local_dept
        $dept_id = explode(',', $dept_id);

        $conditions = [
            ['rpa_customer_managers.jjrNum', '!=', ' '],
            ['rpa_revisit_customers.status', '<', '3']
        ];

        //find jjr customer
        $data = RpaRevisitCustomers::where($conditions)
            ->whereIn('rpa_customer_managers.yybNum', $dept_id)
            ->leftJoin('rpa_customer_managers', 'rpa_customer_managers.ID', '=', 'rpa_revisit_customers.customer_id')
            ->select([
                'rpa_customer_managers.name',
                'rpa_customer_managers.fundsNum',
//                    'rpa_customer_managers.jjrName',
                'rpa_customer_managers.yybName',
                'rpa_revisit_customers.status',
                'rpa_revisit_customers.id'
            ])->get();

        $obj = [];
        $data->map(function($item) use (&$obj) {
            $obj[$item->yybName][] = $item->toArray();
        });

        return $this->ajax_return(200, 'success', $obj);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function tables(Request $request){
        $user = $request->user('api');
        $dept_id = $user->dept_id;

        if(!$dept_id){
            return $this->ajax_return(500, '暂无数据权限');
        }

        //local_dept
        $dept_id = explode(',', $dept_id);

        return SysPhoneTable::whereIn('dept_id', $dept_id)->get(['code']);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getDetail(Request $request)
    {
        //表单验证
        $request->validate([
            'fundsNum' => 'required|integer',
        ]);

        $fundsNum = $request->fundsNum;

        $sql = "select b.JJRBH,b.JJRXM,funcPFS_G_Decrypt(a.DH,'5a9e037ea39f777187d5c98b')DH,funcPFS_G_Decrypt(a.SJ,'5a9e037ea39f777187d5c98b')SJ,funcPFS_G_Decrypt(a.DZ,'5a9e037ea39f777187d5c98b')DZ,funcPFS_G_Decrypt(a.ZJBH,'5a9e037ea39f777187d5c98b')ZJBH from tkhxx a left join futures.txctc_jjr_ygxxcl b on a.zjzh = b.zjzh where a.zjzh = '{$fundsNum}'";
        //get Detail
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => $sql
            ]
        ];

//        $detail = $this->getCrmData($param);

//        $data = '[{"JJRBH":"290107","JJRXM":"\u77f3\u5a9b\u6167","DH":"13120794199","SJ":"13120794199","DZ":"\u4e0a\u6d77\u5e02\u6d66\u4e1c\u65b0\u533a\u4e66\u9662\u9547\u77f3\u6f6d\u8857135\u5f04\u83ca\u6e05\u82d130\u53f7302\u5ba4","ZJBH":"310225198012114839"}]';
//        $data = json_decode($data, true);

//        $detail = $detail[0];
//        $detail['sc_DH'] = str_repeat('*', 7).substr($detail['DH'], 7);
//        $detail['sc_SJ'] = str_repeat('*', 7).substr($detail['SJ'], 7);
//        $detail['sc_ZJBH'] = substr($detail['ZJBH'], 0, 3).str_repeat('*', strlen($detail['ZJBH'])-7).substr($detail['ZJBH'], -4);

        $data = [
            "JJRBH" => "290107",
            "JJRXM"=> "石媛慧",
            "DH"=> "13120794199",
            "SJ"=> "13120794199",
            "DZ"=> "上海市浦东新区书院镇石潭街135弄菊清苑30号302室",
            "ZJBH"=> "310225198012114839",
            "sc_DH"=> "*******4199",
            "sc_SJ"=> "*******4199",
            "sc_ZJBH"=> "310***********4839"
        ];
        $detail = $data;

        return $this->ajax_return(200, 'success', $detail);
    }


    /**
     * @param Request $request
     * @return array|void
     */
    public function mark(Request $request)
    {
        $id = $request->id;
        RpaRevisitCustomers::where('id', $id)->update(['status' => 1]);

        return $this->ajax_return(200, 'success');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function uploadRecords(Request $request)
    {
        //表单验证
        $request->validate([
            'fundsNum' => 'required|integer',
            'recording' => 'file'
        ]);

        $fundsNum = $request->fundsNum;
        $file = $request->file('recording');

        $res = rpa_customer_manager::where('fundsNum', $fundsNum)->get(['id'])->toArray();
        if($file->isValid()){
            $fileName = "rpa/revisit/customer/".$fundsNum;
            $path = Storage::disk('local')->put($fileName, $file);

            if($path){
                try {
                    $revisit = RpaRevisitCustomers::where('customer_id', $res[0]['id'])->firstOrFail();
                    $revisit->update(['status'=>2]);

                    $size = $file->getClientSize();
                    $size = $size/1024;
                    $size = sprintf("%.2f",$size);

                    $record = [
                        'record_id' => $revisit->id,
                        'record_voice' => $path,
                        'record_time' => '',
                        'record_size' => $size,
                        'record_bit' => '',
                        'record_format' => $file->getMimeType(),
                    ];
                    Records::create($record);

                    return $this->ajax_return(200, 'success');
                }
                catch (Exception $e){
                    return $this->ajax_return(500, 'fail');
                }
            }
        }

        return $this->ajax_return(500, 'fail');
    }
}
