<?php


namespace App\Models\Admin\Func\Plugin;


use Illuminate\Database\Eloquent\Model;

class RpaPluginApply extends Model
{
    protected $table = "rpa_plugin_applys";

    public $timestamps = true;
    //黑名单，白名单
    protected $guarded = [];
}