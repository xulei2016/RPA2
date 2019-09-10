<?php

namespace App\Models\Admin\Base\Document;

use Illuminate\Database\Eloquent\Model;

class SysDocumentMenu extends Model
{
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];
}
