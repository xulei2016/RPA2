<?php

namespace App\Http\Controllers\Index\ZT;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * CxYqApplyController class
 *
 * @Description 次席申请+银期变更
 * @author wanghui
 * @since 2019-05-15
 */
class CxYqApplyController extends BaseAdminController
{
    /**
     * 首页
     */
    public function index()
    {
        return view('Index.ZT.index');
    }
}
