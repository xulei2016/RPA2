<?php

namespace App\Models\Admin\Func\Revisit;

use Illuminate\Database\Eloquent\Model;

class RpaRevisitUserRegion extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */

    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];

    /**
     * 获取区域代号
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRegionCode(){
        return $this->hasMany('App\Models\Admin\Func\Revisit\RpaRevisitUserRegion', 'region_code', 'region_code');
    }
}
