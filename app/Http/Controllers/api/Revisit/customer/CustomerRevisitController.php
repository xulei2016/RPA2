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
use App\Models\Admin\Func\Revisit\RpaRevisitUserRegion;

/**
 * 居间客户回访
 *
 * @author HsuLay
 * @create 20200115
 * @package App\Http\Controllers\api\Revisit\customer
 */
class CustomerRevisitController extends BaseApiController
{

    /**
     * @var mixed
     */
    private $user;

    /**
     * @var
     */
    private $recordPath = 'rpa/revisit/customer/';

    /**
     * CustomerRevisitController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->user = $request->user('api');
    }

    /**
     * 用户树状图 - 列表
     *
     * @param Request $request
     * @return array
     */
    public function getRevisitList(Request $request)
    {
        if (!$this->checkUser())
            return $this->ajax_return(500, '权限错误！');

        $dept_id = $this->user->dept_id;

        $dept_id = explode(',', $dept_id);

        $conditions = [
            ['rpa_customer_managers.jjrNum', '!=', ' '],
            ['rpa_revisit_customers.status', '<', '3']
        ];

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
        $data->map(function ($item) use (&$obj) {
            $obj[$item->yybName][] = $item->toArray();
        });

        $res = [];
        foreach ($obj as $k => $v) {
            $res[] = [
                'dept' => $k,
                'customers' => $v
            ];
        }

        return $this->ajax_return(200, 'success', $res);
    }

    /**
     * 根据用户权限获取码表
     *
     * @param Request $request
     * @return array
     */
    public function getTables(Request $request)
    {
        if (!$this->checkUser())
            return $this->ajax_return(500, '权限错误！');

        $user = $this->user;

        $region_codes = RpaRevisitUserRegion::where('api_user', $user->id)->pluck('region_code');

        $tables = SysPhoneTable::whereIn('region_code', $region_codes)->pluck('code')->toArray();
        $tables = implode('|', $tables);

        return $this->ajax_return(200, 'success', $tables);
    }

    /**
     * 客户 - 居间关系详情数据查询
     *
     * @param Request $request
     * @return array
     */
    public function getCustomerDetail(Request $request)
    {
        if (!$this->checkUser())
            return $this->ajax_return(500, '权限错误！');

        //表单验证
        $request->validate([
            'fundsNum' => 'required|integer',
        ]);

        $fundsNum = $request->fundsNum;

        //构造CRM请求数据
        $sql = "select a.SEX,b.JJRBH,b.JJRXM,funcPFS_G_Decrypt(a.DH,'5a9e037ea39f777187d5c98b')DH,funcPFS_G_Decrypt(a.SJ,'5a9e037ea39f777187d5c98b')SJ,funcPFS_G_Decrypt(a.DZ,'5a9e037ea39f777187d5c98b')DZ,funcPFS_G_Decrypt(a.ZJBH,'5a9e037ea39f777187d5c98b')ZJBH from tkhxx a left join futures.txctc_jjr_ygxxcl b on a.zjzh = b.zjzh where a.zjzh = '{$fundsNum}'";
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => $sql
            ]
        ];

        $detail = $this->getCrmData2($param);

        $detail = $detail[0];

        //////////////////测试用 - 强制修改手机号为测试号码///////////////////////
        $detail['DH'] = '18331507823';
        $detail['SJ'] = '18331507823';
        ////////////////////end///////////////////////

        $detail['sc_DH'] = str_repeat('*', 7) . substr($detail['DH'], 7);
        $detail['sc_SJ'] = str_repeat('*', 7) . substr($detail['SJ'], 7);
        $detail['sc_ZJBH'] = substr($detail['ZJBH'], 0, 3) . str_repeat('*', strlen($detail['ZJBH']) - 7) . substr($detail['ZJBH'], -4);


        //测试用例
        // $data = [
        //     "JJRBH" => "290107",
        //     "JJRXM"=> "石媛慧",
        //     "DH"=> "18756571784",
        //     "SJ"=> "18756571784",
        //     "DZ"=> "上海市浦东新区书院镇石潭街135弄菊清苑30号302室",
        //     "ZJBH"=> "310225198012114839",
        //     "sc_DH"=> "*******4199",
        //     "sc_SJ"=> "*******4199",
        //     "sc_ZJBH"=> "310***********4839"
        // ];
        // $detail = $data;

        return $this->ajax_return(200, 'success', $detail);
    }


    /**
     * 标记客户状态
     *
     * @param Request $request
     * @return array|void
     */
    public function mark(Request $request)
    {
        $id = $request->id;

        if (!$this->checkUser($id))
            return $this->ajax_return(500, '权限错误！');

        //表单验证
        $request->validate([
            'id' => 'required|integer',
        ]);

        RpaRevisitCustomers::where('id', $id)->update([
            'status' => 1,
            'revisit_at' => date('Y-m-d H:i:s')
        ]);

        return $this->ajax_return(200, 'success');
    }

    /**
     * 上传录音材料 并 更新客户状态
     *
     * @param Request $request
     * @return array
     */
    public function uploadRecords(Request $request)
    {
        $fundsNum = $request->fundsNum;

        if (!$this->checkUser(0, $fundsNum))
            return $this->ajax_return(500, '权限错误！');

        //表单验证
        $request->validate([
            'fundsNum' => 'required|integer',
            'recording' => 'file'
        ]);

        $file = $request->file('recording');

        $res = rpa_customer_manager::where('fundsNum', $fundsNum)->get(['id'])->toArray();
        if ($file->isValid()) {
            $fileName = $this->recordPath . $fundsNum;
            $path = Storage::disk('local')->put($fileName, $file);

            if ($path) {
                try {
                    $revisit = RpaRevisitCustomers::where('customer_id', $res[0]['id'])->firstOrFail();
                    $revisit->update(['status' => 2]);

                    $size = $file->getClientSize();
                    $size = $size / 1024;
                    $size = sprintf("%.2f", $size);

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
                } catch (Exception $e) {
                    return $this->ajax_return(500, 'fail');
                }
            }
        }

        return $this->ajax_return(500, 'fail');
    }

    /**
     * 权限识别
     *
     * @param int $id
     * @param int $fundsNum
     * @return bool
     */
    private function checkUser(int $id = 0, int $fundsNum = 0)
    {
        $dept_ids = explode(',', $this->user->dept_id);

        if (!$dept_ids)
            return false;

        if ($id || $fundsNum) {

            $column = $id ? 'id' : 'fundsNum';
            $condition = $id ?? $fundsNum;

            //名下客户
            $dept_id = RpaRevisitCustomers::where($column, $condition)->pluck('yybNum');
            if (!in_array($dept_id, $dept_ids))
                return false;
        }

        return true;
    }
}
