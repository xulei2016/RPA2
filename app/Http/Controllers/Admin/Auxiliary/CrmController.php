<?php


namespace App\Http\Controllers\Admin\Auxiliary;


use App\Http\Controllers\base\BaseAdminController;
use Illuminate\Http\Request;

class CrmController extends BaseAdminController
{

    private $viewPrefix = "admin.Auxiliary.Crm.";

    /**
     * 客户个人信息查询页面
     *
     * @return mixed
     */
    public function index()
    {
        return view($this->viewPrefix.'index');
    }

    /**
     *
     * 查询客户信息
     * @param Request $request
     * @return array
     */
    public function queryCrmCustomer(Request $request){
        $zjzh = $request->zjzh;
        $param = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => [
                    ['ZJZH', '=', $zjzh],
                    ['KHZT', '=', 0]
                ]
            ]
        ];
        $result = $this->getCrmData($param);
        if($result) {
            $count = count($result);
            $html = view($this->viewPrefix.'customerInfo', ['info' => $result[$count-1]]);
            return $this->ajax_return(200, 'success', response($html)->getContent());
        } else {
            return $this->ajax_return(500, '找不到该客户信息');
        }
    }

    /**
     * 同步客户信息
     * @param Request $request
     * @return array
     */
    public function syncCrmCustomer(Request $request)
    {
        $zjzh = $request->zjzh;
        $param = [
            'type' => 'customer',
            'action' => 'syncCustomerInfo',
            'param' => [
                'table' => 'KHXX',
                'zjzh' => $zjzh
            ]
        ];
        $result = $this->getCrmData($param);
        return $this->ajax_return(200, $result);
    }

}