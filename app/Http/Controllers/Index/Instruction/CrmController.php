<?php

namespace App\Http\Controllers\Index\Instruction;

use App\Http\Controllers\base\BaseWebController;

class CrmController extends BaseWebController
{

    public $viewPrefix = 'Index.Instruction.Crm.';

    /**
     * 查看休眠客户
     */
    public function showSleepCustomer()
    {
        return view($this->viewPrefix.'showSleepCustomer');
    }

    /**
     * 查看休眠客户 营业部
     */
    public function showSleepCustomerForYyb()
    {
        return view($this->viewPrefix.'showSleepCustomerForYyb');
    }

    /**
     * 查看休眠客户 客户经理
     */
    public function showSleepCustomerForManager()
    {
        return view($this->viewPrefix.'showSleepCustomerForManager');
    }
}