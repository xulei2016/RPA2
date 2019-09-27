<?php
namespace App\Models\Admin\Base\Plugin;

use Illuminate\Database\Eloquent\Model;

class SysPluginVersion extends Model
{
    protected $table = "sys_plugin_versions";

    protected $fillable = ['pid', 'version', 'status','desc','url','show_name'];

    public $timestamps = true;
}