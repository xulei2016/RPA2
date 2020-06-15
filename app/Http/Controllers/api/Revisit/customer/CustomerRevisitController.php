<?php

namespace App\Http\Controllers\api\Revisit\customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Exception;

use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomers;
use App\Models\Admin\Api\Revisit\Customer\RpaRevisitCustomerRecords as Records;
use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Base\Sys\SysPhoneTable;
use App\Models\Admin\Func\Revisit\RpaRevisitUserRegion;
use App\Http\Controllers\api\rpa\SyncCustomerController;
use App\Http\Controllers\api\BaseApiController;

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
    private $version = '1.0.0';

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

        $version = $request->version;
        if (!$this->checkVersion($version))
            return $this->ajax_return(10002, '不支持的版本，请升级后使用！');

        $dept_id = $this->user->dept_id;

        $dept_id = explode(',', $dept_id);

        $conditions = [
            ['rpa_customer_managers.sync_jjr_num', '!=', ' '],
            ['rpa_revisit_customers.status', '<', '3']
        ];

        $data = RpaRevisitCustomers::where($conditions)
            ->whereIn('rpa_customer_managers.sync_yyb_num', $dept_id)
            ->leftJoin('rpa_customer_managers', 'rpa_customer_managers.ID', '=', 'rpa_revisit_customers.customer_id')
            ->select([
                'rpa_customer_managers.name',
                'rpa_customer_managers.fundsNum',
                'rpa_customer_managers.sync_yyb_name',
                'rpa_customer_managers.KHRQ',
                'rpa_revisit_customers.status',
                'rpa_revisit_customers.id',
                'rpa_revisit_customers.failID',
                'rpa_revisit_customers.failDesc',
                'rpa_revisit_customers.revisit_at'
            ])->orderBy('rpa_revisit_customers.status', 'desc')
            ->orderBy('rpa_customer_managers.KHRQ', 'asc')
            ->get();

        $obj = [];
        $data->map(function ($item) use (&$obj) {
            $obj[$item->sync_yyb_name][] = $item->toArray();
        });

        $res = [];
        foreach ($obj as $k => $v) {
            $res[] = [
                'dept' => $k,
                'customers' => $v
            ];
        }

        $rres['dept_lists'] = $res;
        if(count($dept_id) > 2 || in_array($dept_id[0], [
            '10006', //创新
            '10007', //投资
            '11009', //本部
            '10003', //金融期货
            '10004' //商品部
        ])){
            $rres['HeadCompany'] = 1;
        }elseif('103' == $dept_id[0]){
            $rres['HeadCompany'] = 2;
        }else{
            $rres['HeadCompany'] = 0;
        }

        return $this->ajax_return(200, 'success', $rres);
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
        $sql = "select a.SEX,b.bh jjrbh,(select xm from txctc_jjr where id=b.gxr)JJRXM,funcPFS_G_Decrypt(a.DH,'5a9e037ea39f777187d5c98b')DH,
        funcPFS_G_Decrypt(a.SJ,'5a9e037ea39f777187d5c98b')SJ,
        funcPFS_G_Decrypt(a.DZ,'5a9e037ea39f777187d5c98b')DZ,
        funcPFS_G_Decrypt(a.ZJBH,'5a9e037ea39f777187d5c98b')ZJBH
         from tkhxx a 
        left join  futures.txctc_jjrkhgx b on (a.zjzh = b.zjzh and to_char(sysdate,'yyyymmdd') between ksrq and jsrq ) 
        where a.zjzh = '{$fundsNum}'";
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'CJLS',
                'by' => $sql
            ]
        ];

        $detail = $this->getCrmData($param);

        $detail = $detail[0];

        $detail['sc_DH'] = str_repeat('*', 7) . substr($detail['DH'], 7);
        $detail['sc_SJ'] = str_repeat('*', 7) . substr($detail['SJ'], 7);
        $detail['sc_ZJBH'] = substr($detail['ZJBH'], 0, 3) . str_repeat('*', strlen($detail['ZJBH']) - 7) . substr($detail['ZJBH'], -4);

        //客户备注信息
        $conditions = [
            ['rpa_customer_managers.fundsNum', '=', $fundsNum],
        ];

        $data = RpaRevisitCustomers::where($conditions)
            ->leftJoin('rpa_customer_managers', 'rpa_customer_managers.ID', '=', 'rpa_revisit_customers.customer_id')
            ->select([
                // 'rpa_customer_managers.yybNum',
                'rpa_customer_managers.sync_jlr_name as jlrName',
                'rpa_customer_managers.sync_jlr_num as jlrNum',
                'rpa_revisit_customers.failDesc',
                'rpa_revisit_customers.id',
                'rpa_revisit_customers.failID',
                'rpa_revisit_customers.failDesc'
            ])->orderBy('rpa_revisit_customers.status', 'desc')
            ->orderBy('rpa_customer_managers.KHRQ', 'asc')
            ->get()->toArray();

        $detail['failID'] = $data[0]['failID'];
        $detail['failDesc'] = $data[0]['failDesc'];
        $detail['jlrNum'] = $data[0]['jlrNum'];
        $detail['jlrName'] = $data[0]['jlrName'];

        //是否不存在居间人关系信息，不存在则再次更新居间人关系数据
        //客户关系营业部已变更
        // if(!$detail['JJRBH'] || !$detail['JJRXM'] || ($detail['YYB'] != $data[0]['yybNum'])){
        //     (new SyncCustomerController)::syncAppointCustomer(string($fundsNum));
        //     return $this->ajax_return(500, '该客户关系已变更，暂无需回访！', $detail);
        // }

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
        $user = $this->user->name ?? '';

        if (!$user)
            return $this->ajax_return(500, '无效的用户名！');

        if (!$this->checkUser(0, $fundsNum))
            return $this->ajax_return(500, '权限错误！');

        //表单验证
        $request->validate([
            'fundsNum' => 'required|integer',
            // 'recording' => 'file'
        ]);

        $file = $request->file('recording');

        $res = rpa_customer_manager::where('fundsNum', $fundsNum)->pluck('id');
        $revisit = RpaRevisitCustomers::where('customer_id', $res[0])->firstOrFail();

        $isFail = false;
        $failID = 0;
        $failDesc = '';
        //如果获得标记数据，就标记客户并且客户状态改为1  (标记id，备注信息)
        if($request->failID){
            $failID = $request->failID;
            $failDesc = $request->failDesc;
            $isFail = true;
        }

        $revisit->update([
            'status' => $isFail ? 1 : 2,
            'failID' => $failID,
            'failDesc' => $failDesc,
            'reviser' => $user,
            'revisit_at' => date('Y-m-d H:i:s')
        ]);

        if ($file && $file->isValid()) {
            $fileName = $this->recordPath . $fundsNum;
            $path = Storage::disk('local')->put($fileName, $file);

            if ($path) {
                try {
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
        }else{
            return $this->ajax_return(200, 'success');
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
            $condition = $id ?: $fundsNum;

            //名下客户
            $dept_id = RpaRevisitCustomers::where([[$column, '=', $condition]])
                ->leftJoin('rpa_customer_managers', 'rpa_customer_managers.ID', '=', 'rpa_revisit_customers.customer_id')
                ->pluck('rpa_customer_managers.sync_yyb_num');

            if (!in_array($dept_id[0], $dept_ids))
                return false;
        }

        return true;
    }

    /**
     * 检查版本
     * @param string $version
     * @return bool
     */
    private function checkVersion($version = '')
    {
        if (!$version || ($version !== $this->version)) {
            return false;
        }
        return true;
    }
}
