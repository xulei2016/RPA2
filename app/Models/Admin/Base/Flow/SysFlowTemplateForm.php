<?php

namespace App\Models\Admin\Base\Flow;

use Illuminate\Database\Eloquent\Model;

/**
 * SysFlowTemplateForm
 *
 * @Description 模板表单
 */
class SysFlowTemplateForm extends Model
{
    //黑名单，白名单
    protected $guarded = [];

    /**
     * template
     *
     * @return void
     */
    public function template(){
    	return $this->belongsTo('App\Models\Admin\Base\Flow\SysFlowTemplate','template_id');
    }
}
