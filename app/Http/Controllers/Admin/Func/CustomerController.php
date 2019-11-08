<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\rpa_customer_manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Excel;

/**
 * JJRVisController
 * @author hsu lay
 */
class CustomerController extends BaseAdminController{
    //查询页展示
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 开户客户 页");
        //获取客服部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/Customer/index', ['list' => $result]);
    }

    /**
     * add
     */
    public function add(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 开户客户 页");
        return view('admin.func.Customer.add');
    }

    /**
     * adddata
     */
    public function adddata(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 开户客户");
        $data = $this->get_params($request, ['yyb','fundsNum','name','idCard','special','message','customerNum','jjr']);
        if($data['special']){
            $data['special'] = implode(",",$data['special']);
        }
        $guzzle = new Client(['verify'=>false]);
        $host = "https://rpa.slave.haqh.com:8088";
        $token = $this->access_token($host);
        $response = $guzzle->post($host.'/api/v1/sync_data',[
            'headers'=>[
                'Accept' => 'application/json',
                'Authorization' => $token
            ],
            'form_params' => $data
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        return $this->ajax_return($result['status'], $result['msg']);
    }

    /**
     * edit
     */
    public function edit(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 开户客户备注 页");
        $id = $request->id;
        $customer = rpa_customer_manager::where('id','=',$id)->first();
        return view('admin.func.Customer.edit',['customer' => $customer]);
    }

    public function editdata(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 开户客户备注 页");
        $id = $request->id;
        $data = $this->get_params($request, [['message','']]);
        rpa_customer_manager::where("id",$id)->update($data);
        return $this->ajax_return(200, '操作成功！');
    }

    //查询信息
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['customer','dept','manager','mediator','from_add_time','to_add_time']);

        $condition = $this->getPagingList($selectInfo, ['from_add_time'=>'>=','to_add_time'=>'<=']);
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('fundsNum', 'like', "%".$customer."%"));
        }elseif(!empty( $customer )){
            array_push($condition,  array('name', 'like', "%".$customer."%"));
        }
        $dept = $selectInfo['dept'];
        if($dept){
            array_push($condition,  array('yybName', 'like', "%".$dept."%"));
        }
        $manager = $selectInfo['manager'];
        if($manager && is_numeric( $manager )){
            array_push($condition,  array('customerNum', '=', $manager));
        }elseif(!empty( $manager )){
            array_push($condition,  array('customerManagerName', '=', $manager));
        }
        $mediator = $selectInfo['mediator'];
        if($mediator && is_numeric( $mediator )){
            array_push($condition,  array('jjrNum', '=', $mediator));
        }elseif(!empty( $mediator )){
            array_push($condition,  array('jjrName', '=', $mediator));
        }
        $rows = $request->rows;
        $order = $request->sort ?? 'add_time';
        $sort = $request->sortOrder ?? 'desc';
        return rpa_customer_manager::where($condition)->orderBy($order,$sort)->orderBy('id','desc')->paginate($rows);
    }

    //删除
    public function delete(Request $request){
        $ids = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 开户客户");
        $data = explode(',',$ids);
        foreach($data as $v){
            $customer = rpa_customer_manager::where('id','=',$v)->first();
            //1.crm数据作废
            $sql = "update futures.txctc_jjr_ygxxcl set CLZT = 4 where ZJZH = ".$customer->fundsNum;
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXXCL',
                    'by' => $sql
                ]
            ];
            $this->getCrmData($post_data);
            //2.rpa删除
            rpa_customer_manager::where('id','=',$v)->delete();
            //3.内部系统删除
            DB::connection("oa")->table("oa_customer_manager")->where([
                ["fundsNum",'=',$customer->fundsNum],
                ["name","=",$customer->name],
                ['special','=',$customer->special]
            ])->delete();
        }
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * export
     */
    public function export(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 开户客户");
        $selectInfo = $this->get_params($request, ['id','customer','manager','mediator','from_add_time','to_add_time']);
        $condition = $this->getPagingList($selectInfo, ['from_add_time'=>'>=','to_add_time'=>'<=']);
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('fundsNum', '=', $customer));
        }elseif(!empty( $customer )){
            array_push($condition,  array('name', '=', $customer));
        }
        $manager = $selectInfo['manager'];
        if($manager && is_numeric( $manager )){
            array_push($condition,  array('customerNum', '=', $manager));
        }elseif(!empty( $manager )){
            array_push($condition,  array('customerManagerName', '=', $manager));
        }
        $mediator = $selectInfo['mediator'];
        if($mediator && is_numeric( $mediator )){
            array_push($condition,  array('jjrNum', '=', $mediator));
        }elseif(!empty( $mediator )){
            array_push($condition,  array('jjrName', '=', $mediator));
        }

        //设置需要导出的列，以及对应的表头
        $exportList = [
            'name' => '客户名称',
            'fundsNum' => '资金账号',
            'idCard' => '身份证号',
            'yybName' => '所属营业部',
            'customerManagerName' => '客户经理',
            'customerNum' => '客户经理工号',
            'jjrName' => '居间人姓名',
            'jjrNum' => '居间人编号',
            'add_time' => '开户时间',
            'special' => '特殊',
            'message' => '备注',
            'visit_time' => '回访时间',
            'visit_message' => '回访备注',
            'creater' => '操作人',
        ];

        if(isset($selectInfo['id'])){
            $data = rpa_customer_manager::whereIn('id', explode(',',$selectInfo['id']))->select(array_keys($exportList))->get()->toArray();
        }else{
            $data = rpa_customer_manager::where($condition)->select(array_keys($exportList))->get()->toArray();
        }
        //设置表头
        $cellData[] = array_values($exportList);

        foreach($data as $k => $info){
            $special = str_replace("1","仅账户激活",$info['special']);
            $special = str_replace("2","仅账户更新",$special);
            $special = str_replace("3","仅二次金融",$special);
            $special = str_replace("4","仅二次能源",$special);
            $info['special'] = $special;
            
            array_push($cellData, array_values($info));
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 开户客户列表");
        Excel::create('开户客户列表',function($excel) use ($cellData){
            $excel->sheet('客户列表', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
