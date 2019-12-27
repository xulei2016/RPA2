<?php

namespace App\Models\Admin\Func\Archives;

use Illuminate\Database\Eloquent\Model;

class func_archives_files extends Model
{
    protected $table = "func_archives_files";
    /**
     * 关联到模型的数据表
     *
     * @var string
     */

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

}
