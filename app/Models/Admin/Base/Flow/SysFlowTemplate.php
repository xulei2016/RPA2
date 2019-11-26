<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

class SysFlowTemplate extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    /**
     * template_form
     *
     * @return void
     */
    public function template_form(){
    	return $this->hasMany('App\Models\Admin\Base\Flow\SysFlowTemplateForm','template_id');
    }
}
