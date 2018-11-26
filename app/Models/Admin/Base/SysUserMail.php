<?php

namespace App\models\admin\base;

use Illuminate\Database\Eloquent\Model;

class SysUserMail extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    public $timestamps = true;

    //黑名单，白名单
    protected $guarded = [];

    /**
     * 邮件
     */
    public function mails(){
        return $this->hasMany('App\Models\Admin\Base\SysMailOutbox');
    } 
}
