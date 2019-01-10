<?php

namespace App\Http\Controllers\admin\rpa;

use App\Models\Admin\Rpa\rpa_maintenance;
use App\Models\Admin\Rpa\rpa_taskcollections;
use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * rpa 日志模块
 * @author hsu lay
 * @since 2018/05/15
 */
class StatisticsController extends BaseAdminController{

    /*************************rpa日志**********************************/
    //rpa运行日志
    public function rpa_log(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 rpa 日志中心");
        return view('admin/rpa/statistics/log');
    }

    public function show(Request $request, $id)
    {
        $info = rpa_taskcollections::find($id);
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 rpa 日志中心 参数");
        return view('admin.rpa.statistics.show', ['info' => $info]);
    }
    //异步分页数据
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request,["name","from_time","to_time"]);
        $condition = $this->getPagingList($selectInfo, ['name'=>'like','from_time'=>'>=','to_time'=>'<=']);
        $rows = $request->rows;
        return rpa_taskcollections::where($condition)->paginate($rows);
    }
    /*****************************统计中心************************************/
    //统计中心
    public function index(Request $request){
        //饼图
        $count = rpa_taskcollections::count();
        $info['success'] = rpa_taskcollections::where('state','=','成功')->count();
        $info['fail'] = rpa_taskcollections::where('state','=','失败')->count();
        $info['unknown'] = $count - $info['success'] - $info['fail'];
        //条图
        $taskss = rpa_maintenance::pluck('name')->toArray();
        $taskname = rpa_maintenance::get(["name","bewrite"])->toArray();
        foreach($taskss as $task){
            $fail = [['name','=',$task],['state','=','失败']];
            $success = [['name','=',$task],['state','=','成功']];
            $fail_count = rpa_taskcollections::where($fail)->count();
            $success_count = rpa_taskcollections::where($success)->count();
            $tasks['fail'][] = $fail_count;
            $tasks['success'][] = $success_count;

        }
        $tasks['fail'] = implode(',', $tasks['fail']);
        $tasks['success'] = implode(',', $tasks['success']);
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 rpa 统计中心");
        return view('admin/rpa/statistics/index', ['info' => $info, 'tasks' => $tasks ,'taskss' => $taskss, 'taskname' => $taskname]);
    }

    //rpa获取数据
    public function getData(Request $request){
        //按天数统计
        $day = $request->day ?? 7 ;
        $task = $request->task ?? 'zwtx' ;
        $time = date('Y-m-d h:i:s',strtotime("-{$day} day"));
        $condition = [['time','>=',$time],['name','=',$task]];
        $data = rpa_taskcollections::where($condition)->get()->toArray();
        $data = $this->get_one($data);
        $times = [];
        foreach($data as $param){
            $time = date('Y-m-d', strtotime($param['time']));
            if(!in_array($time, $times)){
            }
            if($param['state'] == '失败'){
                $times[$time]['fail'][] = $param['id'];
            }else{
                $times[$time]['success'][] = $param['id'];
            }
        }
        return $times;
    }
}
