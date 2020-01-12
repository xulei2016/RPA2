<?php

namespace App\Http\Controllers\Admin\MDC;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

use App\Models\Admin\MDC\SysLoginRecord;
use DB;

class DAController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $n = 200;
//        $longitudes = [73.33, 135.05];
//        $latitudes = [3.51, 53.33];
//
//        for ($i = 0; $i < 1000; $i++){
//            $array = [
//                'user_id' => 1,
//                'longitude' => self::randomFloat(117.09, 117.39),
//                'latitude' => self::randomFloat(31.69, 30.93)
//            ];
//            SysLoginRecord::create($array);
//        }

        return view('Admin.MDC.DA.index');
    }

    //mock 数据
    public function randomFloat($min = 0, $max = 1)
    {
        $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return sprintf("%.6f", $num);
    }

    /**
     * 位置信息
     *
     * @param Request $request
     * @return SysLoginRecord[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPosition(Request $request)
    {
        $data = $this->get_params($request, ['from_login_time', 'to_login_time']);
        $conditions = $this->getPagingList($data, ['from_login_time' => '>', 'to_login_time' => '<']);
        $this->log(__CLASS__, __FUNCTION__, $request, "获取位置信息");
        return SysLoginRecord::where($conditions)->get(['longitude as lng', 'latitude as lat']);
    }

    /**
     * 频次获取
     *
     * @param Request $request
     * @return mixed
     */
    public function getTfrequency(Request $request)
    {
        $data = $this->get_params($request, ['from_login_time', 'to_login_time']);
        $conditions = $this->getPagingList($data, ['from_login_time' => '>', 'to_login_time' => '<']);
        $months = DB::table('sys_login_records')->where($conditions)->orderBy('months', 'asc')->groupBy('months')->get([DB::raw('DATE_FORMAT(login_time,\'%m\') months'), DB::raw('count(id) count')]);
        $days = DB::table('sys_login_records')->where($conditions)->orderBy('days', 'asc')->groupBy('days')->get([DB::raw('DATE_FORMAT(login_time,\'%H\') days'), DB::raw('count(id) count')]);
        $this->log(__CLASS__, __FUNCTION__, $request, "获取频次数据");
        return $this->ajax_return(200, 'success', compact('months', 'days'));
    }

    /**
     * 获取区域数据
     *
     * @param Request $request
     * @return array
     */
    public function getArea(Request $request){
        $data = $this->get_params($request, ['from_login_time', 'to_login_time']);
        $conditions = $this->getPagingList($data, ['from_login_time' => '>', 'to_login_time' => '<']);
        $area = 'region';//country、region、city
        $region = SysLoginRecord::where($conditions)->orderBy($area, 'desc')->groupBy($area)->get([$area, DB::raw('count(region) count')]);
        $city = SysLoginRecord::where($conditions)->orderBy('city', 'desc')->groupBy('city')->get(['city', DB::raw('count(region) count')]);
        $this->log(__CLASS__, __FUNCTION__, $request, "获取区域数据");
        return $this->ajax_return(200, 'success', compact('region', 'city'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
