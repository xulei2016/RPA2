<?php

namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 合约 接收者
 * Class RpaContractReceiver
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractReceiver extends Model
{
    protected $table = 'rpa_contract_receiver';

    public $timestamps = true;

    protected $guarded = [];

}