<?php

namespace App\Http\Controllers\Admin\Hadmy;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Api\Trade\RpaTradeLoginRecord;
use Illuminate\Http\Request;

class HadmyController extends BaseAdminController{

    // 首页
    public function index(Request $request)
    {
        //总客户数
        $total_count = RpaTradeLoginRecord::groupBy('tzjh_account')->pluck('tzjh_account')->count();
        //在线客户数
        $logout_limit = $this->get_config(['logout_limit']);
        $time = time()-$logout_limit['logout_limit']*60;
        $now_count = RpaTradeLoginRecord::where([["end_time",">=",$time]])->groupBy('tzjh_account')->pluck('tzjh_account')->count();
        //总在线时长
        $total = RpaTradeLoginRecord::get();
        $total_time = 0;
        foreach($total as $v){
            if($v->count_time == ''){
                $total_time += 300;
            }else{
                $total_time += $v->count_time;
            }
        }
        $total_time = (int)($total_time / 60);
        //总登录次数
        $total_login = RpaTradeLoginRecord::count();

        $data = [
            'online' => $now_count,
            'total' => $total_count,
            'total_time' => $total_time,
            'total_login' => $total_login
        ];
        return view('admin/Hadmy/statistics/index',['data'=>$data]);
    }

    //总数信息
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['zjzh','tzjh_account']);
        $condition = [];
        //资金账号
        $zjzh = $selectInfo['zjzh'];
        if(!empty($zjzh)){
            array_push($condition,  array('zjzh', 'like', "%".$zjzh."%"));
        }
        //投资江湖账户
        $tzjh_account = $selectInfo['tzjh_account'];
        if(!empty( $tzjh_account )){
            array_push($condition,  array('tzjh_account', 'like', "%".$tzjh_account."%"));
        }

        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $data = RpaTradeLoginRecord::where($condition)
            ->groupBy('tzjh_account')
            ->paginate($rows,['tzjh_account']);

        //数据整理
        $limit = $this->get_config(['logout_limit']);
        $time = time() - $limit['logout_limit']*60;
        foreach($data as &$v){
            //获取总登录时长
            $customer = RpaTradeLoginRecord::where('tzjh_account',$v->tzjh_account)->get();
            $single_time = 0;
            foreach($customer as $v1){
                if($v1->count_time == ''){
                    $single_time += 300;
                }else{
                    $single_time += $v1->count_time;
                }
            }
            $v->single_time = (int)($single_time / 60);
            $v->zjzh = $customer[0]->zjzh;

            //获取总登录次数
            $single_login = RpaTradeLoginRecord::where('tzjh_account',$v->tzjh_account)->count();
            $v->single_login = $single_login;

            //判断是否在线
            $last = RpaTradeLoginRecord::where('tzjh_account',$v->tzjh_account)->orderBy('start_time','desc')->first();
            if(empty($last->end_time) || $last->end_time > $time){
                $v->online = true;
            }else{
                $v->online = false;
            }
        }
        return $data;
    }

    //单客户
    public function statistics(Request $request)
    {
        $tzjh = $request->tzjh;

        if($tzjh){
            //2.统计单客户的在线时长
            $customer = RpaTradeLoginRecord::where('tzjh_account',$tzjh)->get();
            $single_time = 0;
            foreach($customer as $v){
                if($v->count_time == ''){
                    $single_time += 300;
                }else{
                    $single_time += $v->count_time;
                }
            }
            $total = RpaTradeLoginRecord::get();
            $total_time = 0;
            foreach($total as $v){
                if($v->count_time == ''){
                    $total_time += 300;
                }else{
                    $total_time += $v->count_time;
                }
            }

            $time = [
                'total' => (int)($total_time / 60),
                'single' => (int)($single_time / 60)
            ];
            //3，统计登录次数
            $single_login = RpaTradeLoginRecord::where('tzjh_account',$tzjh)->count();
            $total_login = RpaTradeLoginRecord::count();

            $login = [
                'total' => $total_login,
                'single' => $single_login
            ];

            return view('admin/Hadmy/statistics/statistics',[
                'tzjh_account' => $tzjh,
                'time' => $time,
                'login' => $login
            ]);
        }else{
            return view('errors.404');
        }

    }

    //单个客户信息
    public function single_pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['tzjh_account','ip','mac','version']);
        $condition = $this->getPagingList($selectInfo,['tzjh_account'=>'=','ip'=>'=','mac'=>'=','version'=>'=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $data = RpaTradeLoginRecord::where($condition)->orderBy($order,$sort)->paginate($rows);

        //数据整理
        $limit = $this->get_config(['logout_limit']);
        $time = time() - $limit['logout_limit']*60;
        foreach($data as &$v){

            $v->count_time = (int)($v->count_time / 60);

            //判断是否在线
            if(empty($v->end_time) || $v->end_time > $time){
                $v->online = true;
            }else{
                $v->online = false;
            }

            //时间格式化
            $v->start_time = date("Y-m-d H:i:s",$v->start_time);
            $v->end_time = date("Y-m-d H:i:s",$v->end_time);
        }
        return $data;
    }
}
