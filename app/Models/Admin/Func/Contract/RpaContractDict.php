<?php

namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 合约字典表
 * Class RpaContractDist
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractDict extends Model
{
    protected $table = 'rpa_contract_dict';

    public $timestamps = false;

    protected $guarded = [];

}