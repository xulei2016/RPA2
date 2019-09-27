<?php
namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

/**
 * 客户流程
 * Class RpaKhFlow
 * @package App\Models\Admin\Func
 */
class RpaKhFlow extends Model
{
    public $timestamps = true;

    public $fillable = ['uid', 'tid', 'business_id', 'number', 'status'];
}