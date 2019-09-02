<?php

namespace App\Http\Controllers\Admin\Base\CallCenter;

use App\Models\Admin\Base\CallCenter\SysSetting;
use Illuminate\Http\Request;

class SettingController extends BaseController
{
    private $view_prefix = "admin.base.callCenter.setting.";

    public function index(){
        $settings = SysSetting::where('status', 1)->get()->toArray();
        return view($this->view_prefix.'index', ['settings' => $settings]);;
    }

    public function store(Request $request){
        $data = $request->all();
        foreach ($data as $k => $v) {
            SysSetting::where('name','=', $k)->update(['value' => $v]);
        }
        $this->setConfig();
        return $this->ajax_return(200, '操作成功');
    }
}