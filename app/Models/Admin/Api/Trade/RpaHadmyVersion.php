<?php

namespace App\Models\Admin\Api\Trade;

use Illuminate\Database\Eloquent\Model;

class RpaHadmyVersion extends Model
{
    public $table = 'rpa_hadmy_version';
    public $timestamps = false;

    //黑名单，白名单
    protected $guarded = [];
}
