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

    private $to;

    private $params;

    /**
     * 任务最大尝试次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * SendSMS constructor.
     * @param $to
     * @param $params
     */
    public function __construct($to, $params)
    {
        $this->to = $to;

        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @param SMS $sms
     * @return void
     */
    public function handle(SMS $sms)
    {
        return $sms::send($this->to, $this->params);
    }
}
