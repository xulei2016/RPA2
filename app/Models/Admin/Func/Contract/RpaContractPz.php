<?php

namespace App\Models\Admin\Func\Contract;


use Illuminate\Database\Eloquent\Model;

/**
 * 合约 品种
 * Class RpaContractPz
 * @package App\Models\Admin\Func\Contract
 */
class RpaContractPz extends Model
{
    protected $table = 'rpa_contract_pz';

    public $timestamps = true;

    protected $guarded = [];

    public static function getList(){
        $list = self::select(['id','jys_id','name','code'])->get()->toArray();
        $newList = [];
        foreach($list as $v) {
            $newList[$v['id']] = $v;
        }
        return $newList;
    }
}