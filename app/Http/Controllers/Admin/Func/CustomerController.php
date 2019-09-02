<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\rpa_customer_manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

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
    public function add()
    {
        return view('admin.func.Customer.add');
    }

    /**
     * adddata
     */
    public function adddata(Request $request)
    {
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

    //查询信息
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['customer','manager','mediator','from_add_time','to_add_time']);

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
}
