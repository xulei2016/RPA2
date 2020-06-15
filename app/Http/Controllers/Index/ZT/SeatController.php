<?php
namespace App\Http\Controllers\Index\ZT;

use App\Models\Admin\Api\Tmp\TmpSeatApply;
use App\Models\Admin\Api\Tmp\TmpCustomer;
use App\Models\Admin\Base\SysRole;
use App\Models\Admin\Base\SysMessage;

use Illuminate\Http\Request;

use App\Http\Controllers\api\BaseApiController;

class SeatController extends BaseApiController
{
    protected $status = [
        0 => "已办理",
        1 => "办理中",
        2 => "办理失败",
        3 => "待办理"
    ];
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user_id = $request->session()->get('tmp_customer_id');
            $this->state = $request->session()->get('tmp_customer_state');
            //5s 代表监管休眠
            if ($this->user_id && $this->state == '5') {
                return $next($request);
            } else {
                $res =  ['status'=>200, 'code'=>10004, 'msg'=>'登录身份已失效，即将重新登录'];
                return response()->json($res);
            }
        });
    }
    /**
     * 用户提交次席申请
     * @param Request $request
     * @return Null
     */
    public function createSeatApply(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'certificates_positive' => 'required',
            'certificates_negative' => 'required',
            'business_type' => 'required',
            'counter_type' => 'required'
        ]);

        //保存证件正反面图片
        $fundsNum = TmpCustomer::where('id', $this->user_id)->first()->fundsNum;
        $certificates_positive = $this->savePicture($request->certificates_positive, "/app/rpa/CX/".$fundsNum."/");
        $certificates_negative = $this->savePicture($request->certificates_negative, "/app/rpa/CX/".$fundsNum."/");

        $record = TmpSeatApply::where('user_id', $this->user_id)->whereIn('status', [1 ,3])->count();
        //存在正在办理的业务
        if ($record > 0) {
            $res = ['status'=>200, 'code'=>10001, 'msg'=>'存在正在办理的业务，不可重复申请'];
        } else {
            $data = [
                'user_id' => $this->user_id,
                'certificates_positive' => $certificates_positive,
                'certificates_negative' => $certificates_negative,
                'business_type' => $request->business_type,
                'counter_type' => $request->counter_type,
                'type' => $request->type ?? '' ,
                'status' => 3
            ];
            $re = TmpSeatApply::create($data);
            try {
                $this->createMessage('次席申请', '<p>有新的次席申请</p>');
            } catch (\Exception $e) {
                // do nothing
            }
        
            $res = ['status'=>200,'data'=> $re];
        }

        return response()->json($res);
    }

    /**
     * 创建消息推送消息  mode3:系统共告 type1：角色
     * @param string $title 标题
     * @param string $content 内容
     * @return:
     */
    //
    public function createMessage($title, $content)
    {
        $role = ['BankRelationAdmin'];
        $roleIds = SysRole::whereIn('name', $role)->pluck('id')->toArray();
        if (count($roleIds)) {
            $roleStr = implode(",", $roleIds);
            $data = [
                'mode' => 3,
                'type' => 1,
                'user' => $roleStr,
                'title' => $title,
                'content' => $content,
                'add_time' => $this->getTime()
            ];
            SysMessage::create($data);
        }
    }

    /**
     * 保存一张base64图片
     * @param string $base64 base64图片
     * @param string $dir 目录路径
     * @return:
     */
    public function savePicture($base64, $dir)
    {
        if (strstr($base64, ",")) {
            $base64 = explode(',', $base64);
            $image = $base64[1];

            $prefix = $dir;
            $path = storage_path().$prefix;
            
            //判断目录是否存在 不存在就创建
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $imageName = $this->generateRandomString();
            $fullImageName = $imageName.'.png';
            //图片路径
            $imageSrc= $path.$fullImageName;

            $r = file_put_contents($imageSrc, base64_decode($image));

            return $prefix.$fullImageName;
        }
    }

    /**
     * 获取一个随机字符串
     * @param int $length 长度
     * @return: string
     */
    private function generateRandomString($length = 10)
    {
        $characters = '23456789abcdefghjklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * 获取最近一次 次席申请/取消 数据
     * @param int $length 长度
     * @return: string
     */
    public function lastApplyData()
    {
        $res = TmpSeatApply::where('user_id', $this->user_id)
        ->leftJoin('tmp_customers', 'tmp_seat_applies.user_id', 'tmp_customers.id')
        ->orderBy('tmp_seat_applies.created_at', 'desc')->first();
        $data = ['status'=>200, 'data'=>$res];
        
        return response()->json($data);
    }

    /**
     * 获取最近一次 次席申请/取消 并且成功的数据
     * @param int $length 长度
     * @return: string
     */
    public function lastApplySuccessData()
    {
        $res = TmpSeatApply::where('user_id', $this->user_id)
        ->leftJoin('tmp_customers', 'tmp_seat_applies.user_id', 'tmp_customers.id')
        ->orderBy('tmp_seat_applies.created_at', 'desc')->where('status', 0)->first();
        $data = ['status'=>200, 'data'=>$res];
        
        return response()->json($data);
    }
}
