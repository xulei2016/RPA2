<?php

namespace App\Mail;

use App\models\admin\base\SysMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MdEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $templete = "mail.testmd";

    private $mail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(SysMail $sysmail, $templete = false)
    {
        $this->mail = $sysmail;
        if($templete) $this->templete = $templete;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->mail->file_path){
            return $this->markdown($this->templete)
                ->subject($this->mail->title)
                ->with(['mail'=> $this->mail])
                ->attach($this->mail->file_path);
        }else{
            return $this->markdown($this->templete)
                ->subject($this->mail->title)
                ->with(['mail'=> $this->mail]);
        }

    }
}
