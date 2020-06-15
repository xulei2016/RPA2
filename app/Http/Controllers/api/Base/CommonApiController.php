<?php

namespace App\Http\Controllers\Api\Base;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Admin\Base\ApiUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CommonApiController extends BaseApiController
{
    public function edit_password(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'old_password' => 'required',
            'new_password' => 'required',
        ]);
        if($this->isPWD($request->new_password)){
            $apiuser = ApiUser::where('email',$request->email)->first();
            if($apiuser){
                if(Hash::check($request->old_password,$apiuser->password)){
                    $apiuser->password = bcrypt($request->new_password);
                    $apiuser->save();
                    //删除token
                    DB::table('oauth_access_tokens')->where('user_id',$apiuser->id)->delete();
                    $re = [
                        'status' => 200,
                        'msg' => '修改密码成功！'
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => '原密码错误！'
                    ];
                }
            }else{
                $re = [
                    'status' => 500,
                    'msg' => '该用户名不存在！'
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '密码强度不足，请确认至少需要数字、字母、字母大写小、特殊字符中的三个及以上组合。且长度在8到20位之间！'
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }
}
