<?php


namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 合约指定日期推送
 * Class RpaContractPublishExtra
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractPublishExtra extends Model
{
    protected $table = 'rpa_contract_publish_extra';

    public $timestamps = true;

    protected $guarded = [];
}