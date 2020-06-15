<?php


namespace App\Models\Admin\Rpa;


use Illuminate\Database\Eloquent\Model;

/**
 * 身份证图片识别
 * Class RpaMonitorPicture
 * @package App\Models\Admin\Rpa
 */
class RpaMonitorPicture extends Model
{
    protected $table = "rpa_monitor_picture";

    public $timestamps = false;

    protected $guarded = [];

    // protected $connection = "master2";
}