<?php


namespace App\Services\Common\MSG\Notify;

use App\Mail\MdEmail;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Admin\SysAdminAlert;
use App\models\admin\base\SysMail;
use App\Models\Admin\Base\SysMessage;
use App\Services\Common\MSG\Contracts\NotifyInterface;
use DB;
use Mail;

class ErrNotify implements NotifyInterface
{
    /**
     * @inheritDoc
     */
    public function notify($notify_to, $result)
    {
        $admins = SysAdmin::role($notify_to)->get();

        $content = $this->getContent((array)$result);

        $this->sendManagerAlert($admins, $content);

        $this->sendManagerEmail($admins, $content);

        $this->sendManagerSysNotify($admins, $content);
    }

    /**
     * @param SysAdmin $users
     * @param string $content
     * @return int insertID
     */
    protected function sendManagerAlert($users, string $content)
    {
        $data = [];
        foreach($users as $userID){
            $data[] = [
                'user_id' => $userID->id,
                'title' => 'sys sms error',
                'content' => $content,
                'type' => 'danger'
            ];
        }

        return SysAdminAlert::insert($data);
    }

    /**
     * @param SysAdmin $users
     * @param $content
     * @return mixed
     */
    protected function sendManagerEmail($users, string $content)
    {
        $sendmail = SysMail::create([
            'title' => 'sys sms error',
            'content' => $content,
            'tid' => 3
        ]);

        $data = [];
        $address = [];

        foreach($users as $email){
            $data[] = [
                'mid' => $sendmail->id,
                'uid' => $email->id,
                'type' => 2
            ];
            if(!empty($email->email) && checkEmail($email->email))
                array_push($address, $email->email);
        }

        DB::table('sys_user_mails')->insert($data);

        return Mail::to($address)->send(new MdEmail($sendmail));
    }

    /**
     * @param $users
     * @param string $content
     * @return mixed
     */
    protected function sendManagerSysNotify($users, string $content)
    {
        $data = [
            'title' => '系统通知 - 短信发送异常通知',
            'content' => $content,
            'mode' => 3,
            'type' => 1
        ];
        $userID = [];
        foreach($users as $user){
            array_push($userID, $user->id);
        }
        $data['user'] = implode(',', $userID);
        $data['add_time'] = date('Y-m-d H:i:s');

        return SysMessage::create($data);
    }

    /**
     * @param array $errs
     * @return string
     */
    protected function getContent(array $errs)
    {
        $msg = "尊敬的管理员您好，RPA短信服务异常，请及时登录服务器查看，";

        foreach($errs as $result){
            $exception = $result['exception'] ?: $result;
            $code = $exception->getCode() ?: 000000 ;
            $message = $exception->getMessage() ?: '' ;

            $msg.= "【{$result['gateway']} -- {$message} 错误代码: {$code}】";
        }

        return $msg."【RPA服务平台 - ".date('Y-m-d H:i:s')."】";
    }
}