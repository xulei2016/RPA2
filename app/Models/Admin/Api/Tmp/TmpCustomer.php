<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/4/3
 * Time: 16:45
 */

namespace App\Models\Admin\Api\Tmp;


use Illuminate\Database\Eloquent\Model;

class TmpCustomer extends Model
{

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}