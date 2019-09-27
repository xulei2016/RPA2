<?php

namespace App\Models\Admin\Func\Risk;

use Illuminate\Database\Eloquent\Model;

class FuncRiskQuery extends Model
{
    protected $table = 'func_risk_querys';

    public $timestamps = true;

    protected $fillable = ['rq', 'created_by', 'status'];
}