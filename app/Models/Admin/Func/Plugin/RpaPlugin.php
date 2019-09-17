<?php
namespace App\Models\Admin\Func\Plugin;

use Illuminate\Database\Eloquent\Model;

class RpaPlugin extends Model
{

    protected $table = "rpa_plugins";

    protected $fillable = ['name', 'status', 'desc'];

    public $timestamps = true;
}