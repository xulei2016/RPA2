<?php

namespace App\Http\Controllers\Admin\Base\Chart;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

/**
 * ChartController class
 *
 * @Description
 * @author Hsu Lay
 * @since
 */
class ChartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * 
     */
    public function disk(){
        if(Cache::get('sys_disk')){
            // $info['DISK'] = $this->get_spec_disk('all');
            $info['DISK'] = $this->get_spec_disk('all');

            Cache::forget("sys_disk");
            Cache::add("sys_disk", $info, 3600);
        }
        return Cache::get('sys_disk');
    }

    /**
     * footprint
     */
    public function footprint(){
        //活动内容
        $user_id = auth()->guard('admin')->user()->id;
        $res = DB::select("select count(*)c,simple_desc from sys_logs  where user_id = {$user_id} GROUP BY controller,simple_desc ORDER BY c desc limit 10");
        $data = [];
        $label = [];
        $count = [];
        $all = 0;
        foreach($res as $footprint){
            $all += $footprint->c;
        }
        foreach($res as $footprint){
            $label[] = "{$footprint->simple_desc}(".(round($footprint->c/$all,2)*100)."%),";
            $count[] = $footprint->c;
        }
        $data['pie_labels'] = $label;
        $data['pie_count'] = $count;
        return $data;
    }

    /**
    * 取得磁盘使用情况
    * @return var
    */
	private function get_spec_disk($type = 'system')
	{
        $disk = array();
        switch ($type)
        {
            case 'system':
                //strrev(array_pop(explode(':',strrev(getenv_info('SystemRoot')))));//取得系统盘符
                $disk = self::get_disk_space(strrev(array_pop(explode(':',strrev(getenv('SystemRoot'))))));
                break;
            case 'all':
                foreach (range('b','z') as $letter) {
                    $disk = array_merge($disk, self::get_disk_space($letter));
                }
                break;
            default:
                $disk = self::get_disk_space($type);
                break;
        }
        return $disk;
    }
    
    /**
    * 字节格式化 把字节数格式为B K M G T P E Z Y 描述的大小
    * @param int $size 大小
    * @param int $dec 显示类型
    * @return int
    */
    private function byte_format($size, $dec = 2)
    {
        $a = array("B", "KB", "MB", "GB", "TB", "PB","EB","ZB","YB");
        $pos = 0;
        while ($size >= 1024)
        {
            $size /= 1024;
            $pos++;
        }
        return round($size,$dec);
        // return round($size,$dec)." ".$a[$pos];
    }

    /**
        * 取得单个磁盘信息
        * @param $letter
        * @return array
        */
    private function get_disk_space($letter)
    {
        //获取磁盘信息
        $diskct = 0;
        $disk = array();
        $diskz = 0; //磁盘总容量
        $diskk = 0; //磁盘剩余容量
        $is_disk = $letter.':';

        if(@disk_total_space($is_disk) != NULL){
            $diskct++;
            $disk[$letter][0] = self::byte_format(@disk_free_space($is_disk));
            $disk[$letter][1] = self::byte_format(@disk_total_space($is_disk));
            $disk[$letter][2] = round(((@disk_free_space($is_disk)/(1024*1024*1024))/(@disk_total_space($is_disk)/(1024*1024*1024)))*100,2).'%';
            $diskk = self::byte_format(@disk_free_space($is_disk));
            $diskz = self::byte_format(@disk_total_space($is_disk));
        }
        return $disk;
    }

}
