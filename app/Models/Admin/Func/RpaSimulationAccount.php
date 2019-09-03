<?php
namespace App\Models\Admin\Func;

use Illuminate\Database\Eloquent\Model;

class RpaSimulationAccount extends Model
{
    protected $table = "rpa_simulation_account";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}