<?php

namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

/**
 * 客户基本信息
 * Class RpaKh
 * @package App\Models\Admin\Func
 */
class RpaProfessionChange extends Model
{
    protected $table = "rpa_profession_change";

    public $fillable = ['profession_code', 'status', 'handle_status', 'confirm', 'confirm_time'];

    public $timestamps = true;
}