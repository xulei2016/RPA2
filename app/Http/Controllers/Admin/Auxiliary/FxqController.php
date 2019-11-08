<?php

namespace App\Http\Controllers\Admin\Auxiliary;

use App\Http\Controllers\Base\BaseAdminController;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

/**
 * JJRVisController
 * @author hsu lay
 */
class FxqController extends BaseAdminController{
    //查询页展示
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 反洗钱查询 页");
        return view('admin/Auxiliary/Fxq/index');
    }
}
