<?php
namespace App\Http\Controllers\Admin\Func\Plugin;

/**
 * 下载
 * Class DownloadController
 * @package App\Http\Controllers\Admin\Base\Plugin
 */
class DownloadController extends BaseController
{

    private $view_prefix = "admin.func.plugin.download.";

    public function index(){
        return view($this->view_prefix.'index');
    }
}