<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Api\RpaCustomerInfo;
use App\Models\Admin\Func\rpa_address_recognition;
use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Rpa\rpa_immedtasks;
use DateTime;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewMonitorPictureController extends BaseAdminController
{

    protected $name = "新开户证件识别";

    private $viewPrefix = "admin.func.NewMonitorPicture.";

    private $path = "D:/uploadFile/customerImg/";

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

    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, $this->name . " 列表页");
        return view($this->viewPrefix . 'index');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['zjzh', 'check_status', 'from_created_at', 'to_created_at']);
        $condition = $this->getPagingList($selectInfo, ['zjzh' => '=', 'check_status' => '=', 'from_created_at' => '>=', 'to_created_at' => '<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $sort = $request->sortOrder ?? 'desc';
        $list = rpa_address_recognition::where($condition)->orderBy($order, $sort)->orderBy('id', 'desc')->paginate($rows);
        $count = count($list);
        foreach ($list as $k => $v) {
            if($k < $count - 1 ) {
                $v->next_id = ($list[$k+1])->id;
            } else {
                $v->next_id = null;
            }
        }
        return $list;
    }

    /**
     * 查看
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function show(Request $request, $id)
    {
        $info = rpa_address_recognition::find($id);
        $customerManager = rpa_customer_manager::where('fundsNum', $info->zjzh)->orderBy('id', 'desc')->first();
        $customerImg = RpaCustomerInfo::where('fundAccount', $info->zjzh)->orderBy('id', 'desc')->first();
        $customerImg->sfz_zm = encrypt($customerImg->sfz_zm);
        $customerImg->sfz_fm = encrypt($customerImg->sfz_fm);
        return view($this->viewPrefix . 'show', compact('info', 'customerManager', 'customerImg'));
    }

    /**
     * 审核页面
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function edit(Request $request, $id)
    {
        $info = rpa_address_recognition::find($id);
        $customerManager = rpa_customer_manager::where('fundsNum', $info->zjzh)->orderBy('id', 'desc')->first();

        if(!$customerManager) $customerManager = new rpa_customer_manager();
        if('长期有效' == $customerManager->sfz_date_end) {
            $customerManager->sfz_date_end = '2099-12-31';
        }
        if($customerManager->sfz_date_begin) {
            $customerManager->sfz_date_begin = (new DateTime(str_replace('.', '-', $customerManager->sfz_date_begin)))->format('Y-m-d');
        }

        if($customerManager->sfz_date_end) {
            $customerManager->sfz_date_end = (new DateTime(str_replace('.', '-', $customerManager->sfz_date_end)))->format('Y-m-d');
        }

        $customerImg = RpaCustomerInfo::where('fundAccount', $info->zjzh)->orderBy('id', 'desc')->first();
        $customerImg->sfz_zm = encrypt($customerImg->sfz_zm);
        $customerImg->sfz_fm = encrypt($customerImg->sfz_fm);
        return view($this->viewPrefix . 'edit', compact('info', 'customerManager', 'customerImg'));
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
        $info = rpa_address_recognition::find($id);
        $customerManager = rpa_customer_manager::where('fundsNum', $info->zjzh)->orderBy('id', 'desc')->first();
        if(!$customerManager) $customerManager = new rpa_customer_manager();
        // 已被审核  无法审核
        if(!in_array($info->check_status, [0, 3])) {
            return $this->ajax_return(500, '该记录无法被审核');
        }
        $params['check'] = Auth::user()->realName;
        $params['check_time'] = date("Y-m-d H:i:s");
        // 要做是否需要复核的判断
        if('长期有效' == $params['end_at_final']) {
            $params['end_at_final'] = '20991231';
        } else {
            $params['end_at_final'] = (new DateTime($params['end_at_final']))->format('Ymd');
        }
        $params['start_at_final'] = date('Ymd', strtotime($params['start_at_final']));
        $params['check_status'] = 1;
        rpa_address_recognition::where('id', $id)->update($params);
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
        $info = rpa_address_recognition::find($id);
        $customerImg = RpaCustomerInfo::where('fundAccount', $info->zjzh)->orderBy('id', 'desc')->first();
        $customerImg->sfz_zm = encrypt($customerImg->sfz_zm);
        $customerImg->sfz_fm = encrypt($customerImg->sfz_fm);
        return view($this->viewPrefix . 'review', compact('info', 'customerImg'));
    }

    /**
     * 复核
     * @param Request $request
     */
    public function doReview(Request $request, $id){
        $params = $this->get_params($request, [['check_status', 3]]);
        $info = rpa_address_recognition::find($id);
        // 被审核才能被复核
        if($info->check_status == 1) {
            $person = Auth::user()->realName; // 当前登录用户
            if($info->check == $person) {
                return $this->ajax_return(500, '审核人与复核人不能是同一人');
            }

            $params['review'] = $person;
            $params['review_time'] = date("Y-m-d H:i:s");
            $this->log(__CLASS__, __FUNCTION__, $request, $this->name . " 复核");
            if(3 == $params['check_status']) {
                rpa_address_recognition::where('id', $id)->update($params);
                return $this->ajax_return(200, '已打回');
            } else {
                $params['check_status'] = 2;
                $params['report_status'] = 1; // 待上报
                $params['report_time'] = date('Y-m-d H:i:s');
                rpa_address_recognition::where('id', $id)->update($params);
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
        $result = rpa_address_recognition::where('id', $request->id)->update([
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
     * 展示图片
     * @param Request $request
     */
    public function showImg(Request $request){
        $url = $request->url;
        $url = decrypt($url);
        $path = "D:/uploadFile/customerImg/" . $url;
        if(file_exists($path)) {
            $info = getimagesize($path);
            $mime = $info['mime'];
            header("Content-type:$mime");
            echo file_get_contents($path);
        } else {
            echo "找不到对应的文件";
        }
    }

    /**
     * export
     */
    public function export(Request $request){
        $selectInfo = $this->get_params($request, ['zjzh', 'check_status', 'from_created_at', 'to_created_at', 'id']);
        $condition = $this->getPagingList($selectInfo, ['zjzh' => '=', 'check_status' => '=', 'from_created_at' => '>=', 'to_created_at' => '<=']);
        //设置需要导出的列，以及对应的表头
        $exportList = [
            'zjzh' => '资金账号',
            'address' => '普通识别',
            'address_deep' => '深度识别',
            'check' => '审核人',
            'check_time' => '审核时间',
            'review' => '复核人',
            'review_time' => '复核时间',
            'created_at' => '开户时间',
        ];
        if(isset($selectInfo['id'])){
            $data = rpa_address_recognition::whereIn('id', explode(',',$selectInfo['id']))->select(array_keys($exportList))->get()->toArray();
        }else{
            $data = rpa_address_recognition::where($condition)->select(array_keys($exportList))->get()->toArray();
        }

        //设置表头
        $cellData[] = array_values($exportList);

        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 身份证地址识别");
        Excel::create('身份证地址识别',function($excel) use ($cellData){
            $excel->sheet('客户列表', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
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
}