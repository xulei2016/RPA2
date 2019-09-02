<?php

namespace App\Models\Admin\Rpa;

use Illuminate\Database\Eloquent\Model;

class rpa_filebinarys extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $table="rpa_filebinarys";

    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
