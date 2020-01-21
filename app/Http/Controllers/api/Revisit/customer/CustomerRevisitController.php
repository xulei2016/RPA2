<?php

namespace App\Http\Controllers\api\Revisit\customer;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomerRecords as Records;
use App\Models\Admin\Func\rpa_customer_manager;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;


class CustomerRevisitController extends BaseApiController
{
    private $array = [
        '创新发展部',
        '投资发展部',
        '互联网金融部',
        ''
    ];

    private $local_dept = '公司本部';

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
        $dept = $user->dept()->where('id', $dept_id)->get(['name']);

        //local_dept
        $local_dept = $this->array;
        if (in_array($dept, $local_dept)) {
            $dept = $this->local_dept;
        }

        $conditions = [
            ['rpa_customer_managers.yybNum', 'in', '(39,7)'],
            ['rpa_customer_managers.jjrNum', '!=', ' ']
        ];

        $depts = [
            [
                'name' => '公司本部',
                'num' => 39
            ],
            [
                'name' => '郑州营业部',
                'num' => 7
            ]
        ];

        $newData = [];

        foreach ($depts as $dept) {
            $conditions = [
                ['rpa_customer_managers.yybNum', '=', $dept['num']],
                ['rpa_customer_managers.jjrNum', '!=', ' ']
            ];

            //find jjr customer
            $data = RpaRevisitCustomers::where($conditions)
                ->leftJoin('rpa_customer_managers', 'rpa_customer_managers.ID', '=', 'rpa_revisit_customers.customer_id')
                ->select([
                    'rpa_customer_managers.name',
                    'rpa_customer_managers.fundsNum',
//                    'rpa_customer_managers.jjrName',
                    'rpa_revisit_customers.status',
                    'rpa_revisit_customers.id'
                ])->get();

            $obj = [
                'dept' => $dept['name'],
                'customers' => $data
            ];

            $newData[] = $obj;
        }

        return $this->ajax_return(200, 'success', $newData);
    }


    public function getDetail(Request $request)
    {
        //表单验证
        $request->validate([
            'fundsNum' => 'required|integer',
        ]);

        $sql = '$sql = "select b.JJRBH,b.JJRXM,funcPFS_G_Decrypt(a.DH,\'5a9e037ea39f777187d5c98b\')DH,funcPFS_G_Decrypt(a.SJ,\'5a9e037ea39f777187d5c98b\')SJ,funcPFS_G_Decrypt(a.DZ,\'5a9e037ea39f777187d5c98b\')DZ,funcPFS_G_Decrypt(a.ZJBH,\'5a9e037ea39f777187d5c98b\')ZJBH from tkhxx a left join futures.txctc_jjr_ygxxcl b on a.zjzh = b.zjzh where a.zjzh = \'110003087\'"';
        //get Detail
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => $sql
            ]
        ];

        $detail = BaseAdminController::getCrmData($param);

        $data = '[{"JJRBH":"290107","JJRXM":"\u77f3\u5a9b\u6167","DH":"13120794199","SJ":"13120794199","DZ":"\u4e0a\u6d77\u5e02\u6d66\u4e1c\u65b0\u533a\u4e66\u9662\u9547\u77f3\u6f6d\u8857135\u5f04\u83ca\u6e05\u82d130\u53f7302\u5ba4","ZJBH":"310225198012114839"}]';
        $data = json_decode($data, true);

        return $this->ajax_return(200, 'success', $data);
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
                    $revisit = RpaRevisitCustomers::where('customer_id', 5937)->firstOrFail();
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
