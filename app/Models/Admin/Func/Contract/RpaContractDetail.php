<?php


namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 合约 详细表
 * Class RpaContractDetail
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractDetail extends Model
{
    protected $table = 'rpa_contract_detail';

    public $timestamps = true;

    protected $guarded = [];
}