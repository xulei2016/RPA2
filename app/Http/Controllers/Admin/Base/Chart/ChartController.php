<?php

namespace App\Http\Controllers\Admin\Base\Chart;

use DB;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

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

}
