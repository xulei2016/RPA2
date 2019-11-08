<?php

namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 合约 交易所
 * Class RpaContractJys
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractJys extends Model
{
    protected $table = 'rpa_contract_jys';

    public $timestamps = true;

    protected $guarded = [];

    public static function getList(){
        $list = self::select(['id','name','code'])->get()->toArray();
        $newList = [];
        foreach($list as $v) {
            $newList[$v['id']] = $v;
        }
        return $newList;
    }
}