<?php

namespace App\Jobs;

use App\Models\Admin\Func\rpa_customer_videos;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class File implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $param;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($param)
    {
        $this->param = $param;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customer = rpa_customer_videos::find($this->param['id']);
        $filenewname = $customer->customer_zjzh . $customer->customer_name . $this->param['fileremark'] . ".".$this->param['ext'];

        for($i=0; $i<= $this->param['totalBlobNum']-1; $i++){
            $blob = file_get_contents($this->param['uploadPath'] . $this->param['filename'].'_'.$i);
            file_put_contents($this->param['uploadPath'] . $filenewname,$blob,FILE_APPEND);
            @unlink($this->param['uploadPath'] . $this->param['filename'].'_'.$i);
        }

        //视频信息
        $data =  [
            'remark' => $this->param['fileremark'],
            'path' => $this->param['uploadPath2'] . $filenewname,
            'filename' => $filenewname,
            'state' => 1,
            'add_time' => date("Y-m-d H:i:s",time())
        ];

        if($customer->jsondata){
            $jsondata = json_decode($customer->jsondata,true);
            array_push($jsondata,$data);
            $jsondata = json_encode($jsondata);

        }else{
            $jsondata = json_encode([$data]);
        }
        rpa_customer_videos::where("id",$this->param['id'])->update(['jsondata'=>$jsondata]);
    }
}
