<?php

namespace App\Http\Controllers\Admin\Auxiliary;

use App\Http\Controllers\Base\BaseAdminController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

/**
 * JJRVisController
 * @author hsu lay
 */
class DiscreditController extends BaseAdminController{
    //查询页展示
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 客户失信查询 页");
        return view('admin/Auxiliary/Discredit/index');
    }
    //发布查询任务
    public function search(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "客户失信查询");
        $data = [
            'name' => $request->name,
            'idCard' => $request->idCard,
            'type' => $request->type
        ];
        $guzzle = new Client(['verify'=>false]);
        $host = "https://rpa.slave.haqh.com:8088";
        $token = $this->access_token($host);
        $response = $guzzle->post($host.'/api/v1/test',[
            'headers'=>[
                'Accept' => 'application/json',
                'Authorization' => $token
            ],
            'form_params' => $data
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        return $result;
    }
}
