<?php

namespace App\Models\Index\Common;

use Illuminate\Database\Eloquent\Model;

/**
 * 失信查询记录
 * Class FuncLostCreditRecord
 * @package App\Models\Index\Common
 */
class FuncLostCreditRecord extends Model
{
    protected $table = 'func_lost_credit_records';

    protected $guarded = [];

    // protected $connection = "master2";

}