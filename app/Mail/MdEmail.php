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

    private $template = "mail.testmd";

    private $mail;

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new message instance.
     *
     * @param SysMail $sendmail
     * @param bool $template
     */
    public function __construct(SysMail $sendmail, $template = false)
    {
        $this->mail = $sendmail;
        if($template) $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->mail->file_path){
            return $this->markdown($this->template)
                ->subject($this->mail->title)
                ->with(['mail'=> $this->mail])
                ->attach($this->mail->file_path);
        }else{
            return $this->markdown($this->template)
                ->subject($this->mail->title)
                ->with(['mail'=> $this->mail]);
        }

    }
}
