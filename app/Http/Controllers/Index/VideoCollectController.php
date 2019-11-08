<?php

namespace App\Http\Controllers\Index;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * VideoCollectController class
 *
 * @Description 线下客户视频收集
 * @author Hsu Lay
 * @since 2019-05-15
 */
class VideoCollectController extends BaseAdminController
{
    /**
     * 首页
     */
    public function index()
    {
        //$this->log(__CLASS__, __FUNCTION__, $request, "视频上传 首页");
        return view('Index.VideoCollect.index');
    }

    public function client()
    {
        //$this->log(__CLASS__, __FUNCTION__, $request, "视频上传 客户页");
        return view('Index.VideoCollect.client');
    }

    public function upload()
    {
        return view('Index.VideoCollect.upload');
    }

    public function record()
    {
        return view('Index.VideoCollect.record');
    }

    public function form()
    {
        return view('Index.VideoCollect.form');
    }
}
