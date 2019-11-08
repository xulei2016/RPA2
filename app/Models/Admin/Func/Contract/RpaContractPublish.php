<?php


namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 日期推送
 * Class RpaContractPublish
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractPublish extends Model
{
    protected $table = 'rpa_contract_publish';

    public $timestamps = true;

    protected $guarded = [];
}