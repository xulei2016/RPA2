<?php

namespace App\Jobs\MSG;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use SMSMsg;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $content;

    private $phone;

    private $param;

    private $type;

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * SendSMS constructor.
     * @param $content
     * @param $phone
     * @param string $type
     * @param array $param
     * @param string $callBack
     */
    public function __construct($content, $phone, $type = '', $param = [], string $callBack = '')
    {
        $this->content = $content;

        $this->phone = $phone;

        $this->param = $param;

        $this->type = $type;

        $this->callBack = $callBack;

    }

    /**
     * Execute the job.
     *
     * @param SMSMsg $sms
     * @return void
     */
    public function handle(SMSMsg $sms)
    {
        return $sms::init($this->content, $this->phone, $this->type, $this->param, $this->callBack);
    }
}
