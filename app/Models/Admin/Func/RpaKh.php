<?php

namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

/**
 * 客户基本信息
 * Class RpaKh
 * @package App\Models\Admin\Func
 */
class RpaKh extends Model
{
    public $timestamps = true;

    public $fillable = ['name', 'sfz', 'zjzh', 'phone', 'address', 'postcode', 'email'];
}