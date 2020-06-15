<?php


namespace App\Models\Index\CNode;

use Illuminate\Database\Eloquent\Model;

class FuncAccountProgressQueryRecord extends Model
{

    protected $table = "func_account_progress_query_records";

    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
