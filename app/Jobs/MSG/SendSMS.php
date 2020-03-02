<?php

namespace App\Jobs\MSG;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use SMS;

class SendSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $to;

    /**
     * @var
     */
    protected $msg;

    /**
     * @var
     */
    protected $params;

    /**
     * @var
     */
    protected $gateway;

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * SendSMS constructor.
     *
     * @param $to
     * @param $msg
     * @param $params
     * @param $gateway
     */
    public function __construct($to, $msg, $params = [], $gateway = [])
    {
        $this->to = $to;

        $this->msg = $msg;

        $this->params = $params;

        $this->gateway = $gateway;
    }

    /**
     * Execute the job.
     *
     * @param SMS $sms
     * @return void
     */
    public function handle(SMS $sms)
    {
        return $sms::send($this->to, $this->msg, $this->params, $this->gateway);
    }
}
