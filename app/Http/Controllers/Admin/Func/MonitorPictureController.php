<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Rpa\RpaMonitorPicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 留存客户证件地址识别
 * Class MonitorPictureController
 * @package App\Http\Controllers\Admin\Func
 */
class MonitorPictureController extends BaseAdminController
{

    /**
     * 错误状态
     * @var array
     */
    protected $statusNameList = [
        '0' => '未处理',
        '1' => '全一致',
        '2' => '全不一致',
        '3' => '地址不一致',
        '4' => '有效期不一致',
    ];

    /**
     * 操作状态
     * @var array
     * 0 待审核  1 待复核  2 已上报  3 打回 4 上报成功  5 上报失败
     */
    protected $checkStatusNameList = [
        '0' => '待审核',
        '1' => '待复核',
        '2' => '已上报',
        '3' => '打回',
        '4' => '上报成功',
        '5' => '上报失败',
    ];

    // 页面前缀
    protected $viewPrefix = "admin.func.monitorPicture.";

    protected $name = "留存证件上报";

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, $this->name." 列表页");
        return view($this->viewPrefix.'index');
    }

    /**
     * 分页列表数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['zjzh', 'check_status', 'start_id', 'end_id']);
        $condition = $this->getPagingList($selectInfo, [
            'zjzh' => '=',
        ]);
        if(isset($selectInfo['check_status']) && !is_null($selectInfo['check_status'])) {
            if($selectInfo['check_status'] == 6) {
                $condition[] = ['check_status', '!=', 4];
            } else {
                $condition[] = ['check_status', '=', $selectInfo['check_status']];
            }
        }
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $baseCondition = [
            ['status', '>', 1],
        ];
        $list = RpaMonitorPicture::where($condition)
            ->where($baseCondition);
        if($selectInfo['start_id'] || $selectInfo['end_id']) {
            //id区间条件
            $startId = 1;
            $endId = RpaMonitorPicture::orderBy('id', 'desc')->first()->id;
            if($selectInfo['start_id']) $startId = $selectInfo['start_id'];
            if($selectInfo['end_id']) $endId = $selectInfo['end_id'];
            $list = $list->whereBetween('id', [$startId, $endId]);
        }
        $list = $list->orderBy($order, $sort)
            ->paginate($rows);
        $count = count($list);
        foreach ($list as $k => &$v) {
            $v->statusName = isset($this->statusNameList[$v->status])?$this->statusNameList[$v->status]:'状态未知';
            $v->checkStatusName = isset($this->checkStatusNameList[$v->check_status])?$this->checkStatusNameList[$v->check_status]:'';
            if($k < $count - 1 ) {
                $v->next_id = ($list[$k+1])->id;
            } else {
                $v->next_id = null;
            }
            $v->ZJBH = substr_replace($v->ZJBH, "********", 6, 8);
        }
        return $list;
    }

    /**
     * 审核页面
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function edit(Request $request, $id)
    {
        $info = RpaMonitorPicture::find($id);
        if('长期' == $info->end_at) $info->end_at = '20991231';
        $idCardFront = encrypt($info->sfz_zm);
        $idCardReverse = encrypt($info->sfz_fm);
        return view($this->viewPrefix . 'edit', [
            'info' => $info,
            'sfz_zm' => $idCardFront,
            'sfz_fm' => $idCardReverse,
        ]);
    }

    /**
     * 审核
     * @param Request $request
     * @param $id
     * @return array
     * @desc $checkStatus 0 待审核  1 待复核  2 已上报  3 打回 4 上报成功  5 上报失败
     */
    public function update(Request $request, $id)
    {
        $params = $this->get_params($request, ['address_final', 'start_at_final', 'end_at_final', 'remark']);
        $info = RpaMonitorPicture::find($id);
        $status = $info->status;
        // 已被审核或者  状态不等于2、3、4的时候  无法审核
        if(!in_array($status, [2, 3, 4]) || !in_array($info->check_status, [0, 3])) {
            return $this->ajax_return(500, '该记录无法被审核');
        }
        $params['check'] = Auth::user()->realName;
        $params['check_time'] = date("Y-m-d H:i:s");
        $checkStatus = 2;
        if("长期" == $info->end_at) {
            $info->end_at = "20991231";
        }
        // 要做是否需要复核的判断
        // 地址有修改
        if(!in_array($params['address_final'], [$info->SFZDZ, $info->address])) {
            $checkStatus = 1;
        }
        // 开始时间有修改
        if(!in_array($params['start_at_final'], [$info->crm_zjksrq, $info->start_at])) {
            $checkStatus = 1;
        }
        // 结束时间有修改
        if(!in_array($params['end_at_final'], [$info->crm_zjjsrq, $info->end_at])) {
            $checkStatus = 1;
        }
        $params['check_status'] = $checkStatus;
        if(2 == $checkStatus) { //  不需要复核
            $params['report_status'] = 1; // 待上报
            RpaMonitorPicture::where('id', $id)->update($params);
            $this->sendTask();
        } else {
            RpaMonitorPicture::where('id', $id)->update($params);
        }
        
        $this->log(__CLASS__, __FUNCTION__, $request, $this->name . " 审核");
        return $this->ajax_return(200, '审核成功');
    }

    /**
     * 复核页面
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function reviewView(Request $request, $id){
        $info = RpaMonitorPicture::find($id);
        $idCardFront = encrypt($info->sfz_zm);
        $idCardReverse = encrypt($info->sfz_fm);
        return view($this->viewPrefix . 'review', [
            'info' => $info,
            'sfz_zm' => $idCardFront,
            'sfz_fm' => $idCardReverse
        ]);
    }

    /**
     * 复核
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function doReview(Request $request, $id)
    {
        $params = $this->get_params($request, [['check_status', 3]]);
        $info = RpaMonitorPicture::find($id);
        // 被审核  并且状态是全不一致的时候才能被复核
        if($info->check_status == 1) {
            $person = Auth::user()->realName; // 当前登录用户
            if($info->check == $person) {
                return $this->ajax_return(500, '审核人与复核人不能是同一人');
            }
            $params['review'] = $person;
            $params['review_time'] = date("Y-m-d H:i:s");
            $this->log(__CLASS__, __FUNCTION__, $request, $this->name . " 复核");
            if(3 == $params['check_status']) {
                RpaMonitorPicture::where('id', $id)->update($params);
                return $this->ajax_return(200, '已打回');
            } else {
                $params['check_status'] = 2;
                $params['report_status'] = 1; // 待上报
                $params['report_time'] = date('Y-m-d H:i:s');
                RpaMonitorPicture::where('id', $id)->update($params);
                $this->sendTask();
                return $this->ajax_return(200, '复核成功');
            }
        } else {
            return $this->ajax_return(500, '该记录无法被复核');
        }
    }

     /**
     * 添加备注
     * @param Request $request
     * @return array
     */
    public function addRemark(Request $request){
        $result = RpaMonitorPicture::where('id', $request->id)->update([
            'remark' => $request->remark
        ]);
        if($result) {
            $this->log(__CLASS__, __FUNCTION__, $request, $this->name." 添加备注");
            return $this->ajax_return(200, '添加成功');
        } else {
            return $this->ajax_return(500, '添加失败');
        }
    }

    /**
     * 发任务 立刻任务的时候开启, 目前是定时任务
     */
    private function sendTask()
    {
        return true;
        rpa_immedtasks::create([
            'name' => 'IDValidDateReport',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 展示图片
     * @param Request $request
     */
    public function showImg(Request $request){
        $url = $request->url;
        $url = decrypt($url);
        $urlArray = explode('/', $url);
        $count= count($urlArray);
        $path = "D:/历史图片/6.历史图片/" . $urlArray[$count-2] .'/'. $urlArray[$count-1];
        if(file_exists($path)) {
            $info = getimagesize($path);
            $mime = $info['mime'];
            header("Content-type:$mime");
            echo file_get_contents($path);
        } else {
            echo "找不到对应的文件";
        }
    }
}