<?php
namespace App\Models\Admin\Func\Plugin;

use Illuminate\Database\Eloquent\Model;

class RpaPluginVersion extends Model
{
    protected $table = "rpa_plugin_versions";

    protected $fillable = ['pid', 'version', 'status','desc','url','show_name'];

    public $timestamps = true;
}