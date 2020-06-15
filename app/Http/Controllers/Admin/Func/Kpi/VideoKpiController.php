<?php

namespace App\Http\Controllers\Admin\Func\Kpi;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Rpa\Kpi\RpaKpiTimeToVideos as KpiTimes;
use App\Models\Admin\Rpa\Kpi\RpaKpiVideoQueues as VideoQueues;
use App\Models\Admin\Rpa\Kpi\RpaKpiVideoRecords as VideoRecords;
use Illuminate\Http\Response;

/**
 * Class VideoKpiController
 * 客户服务部 - 视频 kpi 统计
 *
 * @author hsulay
 * @since 2020-06-12
 * @package App\Http\Controllers\Admin\Rpa\Kpi
 */
class VideoKpiController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $KpiTimes = KpiTimes::all();

        $date = self::getDate('today');
        $startDate = $date['startDate'];
        $endDate = $date['startDate'];

        $this->log(__CLASS__, __FUNCTION__, $request, "查看 视频见证KPI 统计");
        return view('admin.rpa.kpi.index');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getVideoTopList(Request $request)
    {
        $param = $request->param;
        $date = self::getDate($param);
        $startDate = $date['startDate'];
        $endDate = $date['startDate'];

        return [
            'name' => '许亮',
            'totalTimes' => 50,
            'totalSuccess' => 49,
            'totalFailed' => 1
        ];
    }

    /**
     * @desc 视频统计
     * @param $st 开始时间
     * @param $et 结束时间
     * @return $list
     */
    private function getVideos($st, $et)
    {
        $kpiTimes = KpiTimes::all();

//        $conditions = []
//        $res = VideoQueues::where($conditions)
//            ->

//        return $res;
    }


    /**
     * @param $param
     * @return array
     */
    private function getDate($param)
    {
        if (is_array($param)) {
            $startDate = $param['startDate'];
            $endDate = $param['endDate'];
        } else {
            $date = date('Y-m-d H:i:s');
            switch ($param) {
                case 'today':
                    $startDate = date('Y-m-d');
                    $endDate = $date;
                    break;
                case 'week':
                    $startDate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y")));
                    $endDate = $date;
                    break;
                case 'month':
                    $startDate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), 1, date("Y")));
                    $endDate = $date;
                    break;
                default:
                    $startDate = date('Y-m-d');
                    $endDate = $date;
                    break;
            }
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
    }

}
