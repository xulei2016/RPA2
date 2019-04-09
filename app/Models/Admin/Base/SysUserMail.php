<?php

namespace App\models\admin\base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        return $this->hasOne(SysMail::class,'id','mid');
    }

    //    未读邮件个数
    public static function mailCount()
    {
        $id = Auth::user()->id;
        return SysUserMail::where([['uid','=',$id],['read_at','=',''],['type','=','2']])->count();
    }
//    未读邮件列表
    public static function mailList(){
        $id = Auth::user()->id;
        return SysUserMail::where([['uid','=',$id],['read_at','=',''],['type','=','2']])->with('mails')->get();
    }

}
