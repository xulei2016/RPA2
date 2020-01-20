<?php

namespace App\Models\Admin\Base;

use Illuminate\Database\Eloquent\Model;

/**
 * 数据字典
 * Class SysDictionaries
 * @package App\Models\Admin\Base
 */
class SysDictionaries extends Model
{
    public $timestamps = false;
    protected $table = 'sys_dictionaries';
    //黑名单，白名单
    protected $guarded = [];
}
