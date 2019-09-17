<?php


namespace App\Models\Admin\Func\Plugin;


use Illuminate\Database\Eloquent\Model;

class RpaPluginDownload extends Model
{
    protected $table = "rpa_plugin_downloads";

    public $timestamps = true;
    //黑名单，白名单
    protected $guarded = [];
}