<?php
namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\api\BaseApiController;
use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Func\RpaSimulationAccount;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * 仿真开户控制器
 * Class Simulation
 * @package App\Http\Controllers\Admin\Func\Simulation
 */
class SimulationController extends BaseAdminController
{

    private $view_prefix = 'admin.func.simulation.';

    /**
     * 列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "仿真开户 列表页");
        return view($this->view_prefix.'index');
    }

    /**
     * 数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['name','startTime','endTime','isCtp']);
        $rows = $request->rows;
        $order = 'rb.'.($request->sort ?? 'id');
        $sort = 'desc';
        $result = RpaSimulationAccount::from('rpa_simulation_account as rb');
        if($selectInfo['name']) {
            $result = $result->where('rb.name','like', "%{$selectInfo['name']}%");
        }

        if($selectInfo['isCtp']) {
            $result = $result->where('rb.name','=', "{$selectInfo['name']}");
        }

        if($selectInfo['startTime']) {
            $result = $result->whereDate('rb.created_at','>=', $selectInfo['startTime']." 00:00:00");
        }

        if($selectInfo['endTime']) {
            $result = $result->whereDate('rb.created_at','<=', $selectInfo['endTime']. " 23:59:59");
        }
        $result = $result->select(['rb.*'])
            ->orderBy($order, $sort)
            ->paginate($rows);
        return $result;
    }

    /**
     * 设置资金账号
     */
    public function setZjzh(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "仿真开户 修改资金账号");
        $has = RpaSimulationAccount::where('zjzh', $request->zjzh)->first();
        if($has) return $this->ajax_return(500, '该资金账号已存在');
        $account = RpaSimulationAccount::where('id', $request->id)->first();
        if(!$account) return $this->ajax_return(500, '未找到对应记录');
        if($account->zjzh) return $this->ajax_return(500, '该用户资金账号已经存在');
        $account->zjzh = $request->zjzh;
        $res = $account->save();
        if($res){
            $kh = RpaSimulationAccount::find($request->id);
            $content = "尊敬的".$account->name."：您好，您的仿真期权账号为".$request->zjzh."，初始密码为身份证后六位，可于下一个交易日参与交易。请到华安期货官网-软件下载-其他及模拟仿真栏目下载软件，推荐下载“期权仿真恒生-5.0”";
            $res = $this->sendSmsSingle($account->phone,$content, 'MNFZ');
            if($res === true){
                return $this->ajax_return(200, '成功');
            }else{
                return $this->ajax_return(500, '数据更新成功，短信发送失败');
            }
        }else{
            return $this->ajax_return(500, '数据更新失败');
        }
    }

    /**
     * 编辑界面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $info = RpaSimulationAccount::from('rpa_simulation_account as rb')
                ->where('rb.id', $id)
                ->leftJoin('rpa_kh_flows as rf', 'rf.business_id', '=', 'rb.id')
                ->leftJoin('rpa_khs as rk', 'rk.id', '=', 'rf.uid')
                ->select(['rb.*','rk.name','rk.sfz','rk.phone','rk.created_at','rk.address','rk.postcode','rk.email'])
                ->first();
        return view($this->view_prefix.'edit', ['info' => $info, 'id' => $id]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, ['zjzh','id']);
        $user_id = (int) auth()->guard('admin')->user()->id;
        $data['updated_by'] = $user_id;
        $result = RpaSimulationAccount::where('id', $data['id'])->update($data);
        $this->log(__CLASS__, __FUNCTION__, $request, "修改 仿真开户");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * 发送短信
     * @param Request $request
     * @return array
     */
    public function sendSms(Request $request){
        $data = $this->get_params($request, ['id', 'type']);
        $result = RpaSimulationAccount::from('rpa_simulation_account as rb')
            ->where('rb.id', $data['id'])
            ->leftJoin('rpa_kh_flows as rf', 'rf.business_id', '=', 'rb.id')
            ->leftJoin('rpa_khs as rk', 'rk.id', '=', 'rf.uid')
            ->select(['rb.*','rk.name','rk.sfz','rk.phone','rk.created_at','rk.address','rk.postcode','rk.email'])->first()->toArray();
        $phone = $result['phone'];
        $name = $result['name'];
        if($data['type'] == 'notice') {
            if($result['is_notice']) {
                return $this->ajax_return(500, '该客户已经发送过通知短信');
            }
            if(!$result['zjzh']) {
                return $this->ajax_return(500, '资金账号不存在');
            }
            $content = "尊敬的{$name}客户：您好，您的仿真期权账号为{$result['zjzh']}，初始密码为身份证后六位，请在华安期货官网-软件下载-期权仿真软件，下载软件，可于下一个交易日参与交易，参与商品期权需满足10天20笔的仿真记录并具有行权经历，祝您交易愉快。(仅允许交易豆粕、白糖的期货和期权，其他品种请勿开仓)";
            $result = $this->sendSmsSingle($phone, $content, 'MNFZ');
            if($result === true) {
                RpaSimulationAccount::where('id', $data['id'])->update([
                    'is_notice' => 1
                ]);
                return $this->ajax_return(200, '短信发送成功');
            } else {
                return $this->ajax_return(500, '短信发送失败');
            }
        }
        if($result['is_sms']) {
            return $this->ajax_return('500', '该客户已经发送过短信');
        }

        $zz = false;
        $dl = false;
        //郑州是否达标
        if(!empty($result['zz_date']) && $result['zz_xq'] > 0){
            $zz = true;
        }

        if(substr($result['dl_db'],0,6) == '达到') {
            $dl = true;
        }
        $date = date("Y年m月d日",strtotime($result['updated_at']));
        if($dl){
            if($zz){
                $content="尊敬的".$name."：恭喜您已成功完成白糖、豆粕仿真期权交易10天20笔的条件，可携带证件来现场开户，商品期权开户需通过测试（达到90分）。开户咨询：0551-62839083";
            }else{
                $content = "尊敬的".$name."：截至".$date."，您的仿真期权交易情况：豆粕".$result['dl_days']."天".$result['dl_amount']."笔（已满足10天20笔，可携带证件来现场开户），白糖".$result['zz_days']."天".$result['zz_amount']."笔（未达到，请及时完成）。开户咨询：0551-62839083";
            }
        }else{
            if($zz){
                $content="尊敬的".$name."：截至".$date."，您的仿真期权交易情况：白糖".$result['zz_days']."天".$result['zz_amount']."笔（已满足10天20笔，可来现场开户），豆粕".$result['dl_days']."天".$result['dl_amount']."笔（未达到，请及时完成）。开户咨询：0551-62839083";
            }else{
                $content = "尊敬的".$name."：开立期权账户需满足该品种10天20笔期权仿真交易，截至".$date."，您的仿真期权交易情况：白糖".$result['zz_days']."天".$result['zz_amount']."笔，豆粕".$result['dl_days']."天".$result['dl_amount']."笔，请及时完成即可来现场开户。开户咨询：0551-62839083";
            }
        }
        $result = $this->sendSmsSingle($phone, $content, 'MNFZ');
        if($result === true) {
            RpaSimulationAccount::where('id', $data['id'])->update([
                'is_sms' => 1
            ]);
            return $this->ajax_return(200, '短信发送成功');
        } else {
            return $this->ajax_return(500, '短信发送失败');
        }

    }

    /**
     * 一键发送短信
     */
    public function sendAll(Request $request){
        $list = RpaSimulationAccount::from('rpa_simulation_account as rb')
            ->where('rb.is_sms', 0)
            ->leftJoin('rpa_kh_flows as rf', 'rf.business_id', '=', 'rb.id')
            ->leftJoin('rpa_khs as rk', 'rk.id', '=', 'rf.uid')
            ->select(['rb.*','rk.name','rk.sfz','rk.phone','rk.created_at','rk.address','rk.postcode','rk.email'])
            ->get()->toArray();
        $count = count($list);
        $successCount = 0;
        $failureCount = 0;
        foreach ($list as $k => $v) {
            $zz = false;
            $dl = false;
            $date = date("Y年m月d日",strtotime($v['updated_at']));
            if($dl){
                if($zz){
                    $content="尊敬的".$v['name']."：恭喜您已成功完成白糖、豆粕仿真期权交易10天20笔的条件，可携带证件来现场开户，商品期权开户需通过测试（达到90分）。开户咨询：0551-62839083";
                }else{
                    $content = "尊敬的".$v['name']."：截至".$date."，您的仿真期权交易情况：豆粕".$v['dl_days']."天".$v['dl_amount']."笔（已满足10天20笔，可携带证件来现场开户），白糖".$v['zz_days']."天".$v['zz_amount']."笔（未达到，请及时完成）。开户咨询：0551-62839083";
                }
            }else{
                if($zz){
                    $content="尊敬的".$v['name']."：截至".$date."，您的仿真期权交易情况：白糖".$v['zz_days']."天".$v['zz_amount']."笔（已满足10天20笔，可来现场开户），豆粕".$v['dl_days']."天".$v['dl_amount']."笔（未达到，请及时完成）。开户咨询：0551-62839083";
                }else{
                    $content = "尊敬的".$v['name']."：开立期权账户需满足该品种10天20笔期权仿真交易，截至".$date."，您的仿真期权交易情况：白糖".$v['zz_days']."天".$v['zz_amount']."笔，豆粕".$v['dl_days']."天".$v['dl_amount']."笔，请及时完成即可来现场开户。开户咨询：0551-62839083";
                }
            }
            $r = $this->sendSmsSingle($v['phone'], $content, 'MNFZ');
            if($r === true) {
                RpaSimulationAccount::where('id', $v['id'])->update([
                    'is_sms' => 1
                ]);
                $successCount++;
            } else {
                $failureCount++;
            }
        }
        return $this->ajax_return(200, '操作成功', [
            'count' => $count,
            'successCount' => $successCount,
            'failureCount' => $failureCount
        ]);
    }

    public function ctp(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "仿真开户 分配ctp");
        $result = RpaSimulationAccount::where('id', $request->id)->first();
        if($result->isCtp != 1) {
            return $this->ajax_return(500, '该记录无法修改ctp状态');
        }
        $result->isCtp = 2;
        $result->ctp_person = auth()->guard('admin')->user()->realName;
        $result->ctp_time = date('Y-m-d H:i:s');
        $r = $result->save();
        if($r) {
            return $this->ajax_return(200, '成功');
        } else {
            return $this->ajax_return(500, '失败');
        }
    }

    /**
     * 导出
     * @param Request $request
     * @throws \PHPExcel_Exception
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['condition', 'name', 'startTime', 'endTime']);
        $data = RpaSimulationAccount::from('rpa_simulation_account as rb')
            ->leftJoin('rpa_kh_flows as rf', 'rf.business_id', '=', 'rb.id')
            ->leftJoin('rpa_khs as rk', 'rk.id', '=', 'rf.uid');
        if($param['condition'] == 'all') {

        } elseif($param['condition'] == 'current') {
            $data = $data->whereDate('rb.created_at', '=', date('Y-m-d'));
        } else {
            if($param['name']) {
                $data = $data->where('rk.name','like', "%{$param['name']}%");
            }
            if($param['startTime']) {
                $data = $data->whereDate('rb.created_at','>=', $param['startTime']." 00:00:00");
            }
            if($param['endTime']) {
                $data = $data->whereDate('rb.created_at','<=', $param['endTime']. " 23:59:59");
            }
        }
        $data = $data->select(['rb.*','rk.name','rk.sfz','rk.phone','rk.created_at','rk.address','rk.postcode','rk.email'])
            ->get()->toArray();
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M');
        $cellNum = count($cellName);
        $fileName = '仿真期权查询数据';
        $expCellName = ['姓名', '身份证', '电话', '录入时间', '资金账号', '大连交易天数', '大连交易笔数', '大连行权数', '郑州交易天数', '郑州交易笔数', '郑州行权数', '来源', '是否发送过短信'];
        $dataList = [];
        foreach ($data as $k => $v) {
            $item = [
                $v['name'],
                $v['sfz'],
                $v['phone'],
                $v['created_at'],
                $v['zjzh'],
                $v['dl_days'],
                $v['dl_amount'],
                $v['dl_xq'],
                $v['zz_days'],
                $v['zz_amount'],
                $v['zz_xq'],
                $v['from'],
                $v['is_sms']?'是':'否',
            ];
            $dataList[] = $item;
        }

        $dataNum = count($dataList);
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i])->getStyle($cellName[$i].'1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //设置宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        //样式
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        for($i=0;$i<$dataNum;$i++){
            for($j=0;$j<$cellNum;$j++){
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].($i+2), $dataList[$i][$j]);
            }
        }
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$fileName.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

}