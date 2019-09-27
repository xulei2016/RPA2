<?php
namespace App\Http\Controllers\Admin\Base\Plugin;

/**
 * 下载
 * Class DownloadController
 * @package App\Http\Controllers\Admin\Base\Plugin
 */
class DownloadController extends BaseController
{

    private $view_prefix = "admin.base.plugin.download.";

    public function index(){
        return view($this->view_prefix.'index');
    }
}