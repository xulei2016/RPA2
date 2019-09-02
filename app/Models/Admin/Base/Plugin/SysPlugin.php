<?php
namespace App\Models\Admin\Base\Plugin;

use Illuminate\Database\Eloquent\Model;

class SysPlugin extends Model
{

    protected $table = "sys_plugins";

    protected $fillable = ['name', 'status', 'desc'];

    public $timestamps = true;
}