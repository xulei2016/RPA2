<?php

namespace App\Models\Admin\Base;

use Illuminate\Database\Eloquent\Model;

class SysMenu extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = true;

    //黑名单，白名单
    // protected $fillable = ['name'];
    protected $guarded = [];
}
