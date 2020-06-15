<?php

namespace App\Http\Controllers\Admin\Mediator;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Base\Organization\SysDept;
use App\Models\Index\Mediator\FuncMediatorFlow;
use App\Models\Index\Mediator\FuncMediatorInfo;
use App\Models\Index\Mediator\FuncMediatorStep;
use App\Models\Index\Mediator\FuncMediatorChangeList;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Base\SysDictionaries;
use Excel;


class MediatorController extends BaseAdminController{

    public $debug = false;
    //首页
    public function index(Request $request)
    {
        $dept = $this->getDept();
        return view('admin/mediator/index',['dept' => $dept]);
    }

    //分页
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['status','flow_status','dept_id','mediator','remark','startTime','endTime']);

        $condition = $this->getPagingList($selectInfo, ['status'=>'=','dept_id'=>'=']);

        //居间人姓名或编号
        $mediator = $selectInfo['mediator'];
        if($mediator && is_numeric( $mediator )){
            array_push($condition, ['number','like',"%".$mediator."%"]);
        }elseif(!empty( $mediator )){
            array_push($condition, ['name','like',"%".$mediator."%"]);
        }

        //流程状态
        $ids = [];
        $where = [
            ['status',1],
            ['part_b_date','<>','']
        ];
        $flow_status = $selectInfo['flow_status'];
        if($flow_status){
            switch ($flow_status){
                case 1:
                    $where[] = ['is_check',0];
                    $where[] = ['is_back',0];
                    break;
                case 2:
                    $where[] = ['is_check',1];
                    $where[] = ['is_sure',0];
                    break;
                case 3:
                    $where[] = ['is_sure',1];
                    $where[] = ['is_handle',0];
                    break;
                case 4:
                    $where[] = ['is_handle',1];
                    break;
                case 5:
                    $where[] = ['is_back',1];
                    break;
                default:
                    break;
            }
        }

        //申请时间
        $start = $selectInfo['startTime'];
        $end = $selectInfo['endTime'];
        if($start){
            $where[] = ['part_b_date','>=',$start];
        }
        if($end){
            $where[] = ['part_b_date','<',$end];
        }
        if($selectInfo['remark']){
            $where[] = ['remark','like',"%".$selectInfo['remark']."%"];
        }

        $ids = FuncMediatorFlow::where($where)->orderBy('updated_at','desc')->pluck('uid')->toArray();
        $ids_ordered = implode(',',$ids);

        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';

        $info = FuncMediatorInfo::with('dept')->where($condition)->whereIn('id',$ids)->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->orderBy($order,$sort)->paginate($rows);
        
        foreach($info as $k=>$v){
            $flow = FuncMediatorFlow::where('uid',$v->id)->orderBy('id','desc')->first();
            $info[$k]->flow = $flow;
        }
        return $info;
    }

    //详情
    public function info(Request $request)
    {
        $id = $request->id;
        $info = FuncMediatorInfo::with("dept")->where('id',$id)->first();
        return view('admin/mediator/info',['info' => $info]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        //流程
        $info = FuncMediatorFlow::where('id',$id)->first();
        //部门
        $dept = $this->getDept();
        //教育背景
        $edu_background = SysDictionaries::where('type','education')->get();
        //职业
        $profession = SysDictionaries::where('type','profession')->get();
        //银行
        $bank_list = SysDictionaries::where('type','bank')->get();
        return view('admin/mediator/edit',['info' => $info, 'dept' => $dept, 'edu_background' =>$edu_background, 'profession' => $profession, 'bank_list' => $bank_list]);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $data = $this->get_params($request, [
            'sex','dept_id','manager_number','number','email','edu_background','address',
            'postal_code','profession','exam_number','birthday','sfz_date_end','zjbh',
            'sfz_address','bank_name','bank_branch','bank_username','bank_number'
        ],false);
        FuncMediatorFlow::where('id',$id)->update($data);
        return $this->ajax_return(200, '操作成功！');
    }

    //履历页面
    public function history(Request $request)
    {
        $id = $request->id;
        return view('admin/mediator/history',['id' => $id]);
    }

    //历史记录分页
    public function history_list(Request $request)
    {
        $selectInfo = $this->get_params($request, ['uid','type','flow_status','startTime','endTime']);
        $condition = $this->getPagingList($selectInfo, ['uid'=>'=','type'=>'=']);

        $flow_status = $selectInfo['flow_status'];
        if($flow_status){
            switch ($flow_status){
                case 1:
                    $condition[] = ['is_check',0];
                    break;
                case 2:
                    $condition[] = ['is_check',1];
                    $condition[] = ['is_sure',0];
                    break;
                case 3:
                    $condition[] = ['is_sure',1];
                    $condition[] = ['is_handle',0];
                    break;
                case 4:
                    $condition[] = ['is_handle',1];
                    break;
                default:
                    break;
            }
        }

        //申请时间
        $start = $selectInfo['startTime'];
        $end = $selectInfo['endTime'];
        if($start){
            $condition[] = ['part_b_date','>=',$start];
        }
        if($end){
            $condition[] = ['part_b_date','<',$end];
        }

        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $res = FuncMediatorFlow::with('dept')->with('info')->where($condition)->orderBy($order,$sort)->paginate($rows);
        return $res;
    }

    //流程详情
    public function flow_info(Request $request)
    {
        $id = $request->id;
        $info = FuncMediatorFlow::where('id',$id)->first();
        if($info->type == 2){
            $changelist = FuncMediatorChangeList::where('fid',$info->id)->get();
            return view('admin/mediator/flow_info2',['info' => $info,'changelist' => $changelist]);
        }else{
            return view('admin/mediator/flow_info',['info' => $info]);
        }
    }

    //审核页面
    public function check(Request $request)
    {
        $id = $request->id;
        $flow = FuncMediatorFlow::where('id',$id)->first();
        //可以打回的步骤
        $steps = FuncMediatorStep::where('can_back',1)->get();
        return view('admin/mediator/check',['id' => $id, 'steps' => $steps, 'flow' => $flow]);
    }

    //审核
    public function check_data(Request $request)
    {
        $special_rate = isset($request->special_rate) ? $request->special_rate : 1;
        $id = $request->id;
        $flow = FuncMediatorFlow::with('dept')->with('info')->where('id',$id)->first();
        if(isset($request->status) && $request->status == 1){
            //检查居间编号是否已存在
            $r = FuncMediatorFlow::where('number',$request->number)->get();
            foreach($r as $v){
                if($v->uid != $flow->uid){
                    return $this->ajax_return(500, '居间编号已存在！');
                }
            }
            //通过
            $update = [
                'rate' => $request->rate,
                'number' => $request->number,
                'remark' => $request->remark,
                'special_rate' => $special_rate,
                'is_check' => '1',
                'check_time' => date('Y-m-d H:i:s'),
                'check_person' => Auth::guard('admin')->user()->realName
            ];
            $flow->number = $request->number;
            //加入回访
            if(isset($request->is_review) && $request->is_review == 1){
                $update['is_review'] = 1;
            }
            //是否需要确认比例
            //是否是特殊比例
            if($special_rate == 1){
                //发送短信，待确认比例
                $content = "您好！您在我公司申请的居间协议已通过初步审核！请您凭手机号再次登陆居间申请系统确认居间返佣比例。注：确认居间返佣比例后方可生成居间编号及居间协议，请收到此短信后务必及时登陆确认。如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
                if(!$this->debug) $this->sendSmsSingle($flow->info->phone, $content, 'JJR-KF');
            }else{
                if($request->rate <= 70){
                    //判断居间比例是否发生变化
                    if($request->rate != $flow->rate){
                        //发送短信，待确认比例
                        $content = "您好！您在我公司申请的居间协议已通过初步审核！请您凭手机号再次登陆居间申请系统确认居间返佣比例。注：确认居间返佣比例后方可生成居间编号及居间协议，请收到此短信后务必及时登陆确认。如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
                        if(!$this->debug) $this->sendSmsSingle($flow->info->phone, $content, 'JJR-KF');
                    }else{
                        //同步数据到crm
                        $this->to_crm($flow,$request->rate);
    
                        $update['is_sure'] = 1;
                        $update['sure_time'] = date('Y-m-d H:i:s');
                    }
                }else{
                    //同步数据到crm
                    $this->to_crm($flow,$request->rate);
    
                    $update['is_sure'] = 1;
                    $update['sure_time'] = date('Y-m-d H:i:s');
                }
            }
            
            FuncMediatorFlow::where('id',$id)->update($update);
        }else{
            //打回
            $update = [
                'is_back' => 1,
                'back_list' => implode(',',$request->back),
                'back_time' => date('Y-m-d H:i:s'),
                'back_person' => Auth::guard('admin')->user()->realName
            ];
            FuncMediatorFlow::where('id',$id)->update($update);
            //发短信
            if(isset($request->is_send) && $request->is_send == 1){
                if(!$this->debug) $this->sendSmsSingle($flow->info->phone,$request->send_tpl, 'JJR-KF');
            }
        }
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * 显示图片
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function showImage(Request $request)
    {
        $url = $request->get('url');
        $url = Crypt::decrypt($url);
        $url = storage_path().config('mediator.file_root').$url;
        return new BinaryFileResponse($url);
    }

    /**
     * 旋转图片
     */
    public function rotateImg(Request $request)
    {
        $id = $request->id;
        $flow = FuncMediatorFlow::where('id',$id)->first();
        $root = storage_path().config('mediator.file_root');
        $result = getimagesize($root.$flow->sign_img);
        $result=explode('/',$result['mime']); 
        $ext=$result[1]; 
        //判断文件后缀
        if($ext == 'png'){
            $func1 = 'imagecreatefrompng';
            $func2 = 'imagepng';
        }elseif($ext == 'jpg' || $ext == 'jpeg'){
            $func1 = 'imagecreatefromjpeg';
            $func2 = 'imagejpeg';
        }elseif($ext == 'gif'){
            $func1 = 'imagecreatefromgif';
            $func2 = 'imagegif';
        }else{
            $func1 = 'imagecreatefromjpeg';
            $func2 = 'imagejpeg';
        }
        $source = $func1($root.$flow->sign_img);
        $rotate = imagerotate($source,90,0);
        if($rotate){
            $func2($rotate,$root.$flow->sign_img);
            $re = [
                'status' => 200,
                'msg' => '图片旋转成功'
            ];
        }else{
            $re= [
                'status' => 500,
                'msg' =>'图片旋转失败'
            ];
        }
        return response()->json($re);
    }

    /**
     * 文件下载
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function download(Request $request)
    {
        $root = storage_path().config('mediator.file_root');
        $id = $request->id;
        $flow = FuncMediatorFlow::where('id',$id)->first();
        //1.创建并打开压缩包
        $zip = new \ZipArchive();
        $name = $flow->info->name."_".$flow->info->number.".zip";
        $zip->open($name,\ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        //2.向压缩包添加文件
        $options = [
            'remove_path' => $root
        ]; 
        $zip->addGlob($root.dirname($flow->sign_img)."/*.*",GLOB_BRACE,$options);
        //3.关闭压缩包
        $zip->close();
        //4.输出
        return response()->download($name)->deleteFileAfterSend(true);
    }

    /**
     * 文件下载全部
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function downloadAll(Request $request)
    {
        $root = storage_path().config('mediator.file_root');
        $id = $request->id;
        $info = FuncMediatorInfo::where('id',$id)->first();
        //1.创建并打开压缩包
        $zip = new \ZipArchive();
        $name = $info->name."_".$info->number.".zip";
        $zip->open($name,\ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        //2.向压缩包添加文件
        $options = [
            'remove_path' => $root
        ]; 
        $zip->addGlob($root.dirname(dirname($info->sign_img))."/*/*.*",GLOB_BRACE,$options);
        //3.关闭压缩包
        $zip->close();
        //4.输出
        return response()->download($name)->deleteFileAfterSend(true);
    }

    /**
     * 同步居间到crm
     * @param $data
     * @return mixed
     */
    public function to_crm($data,$rate = false)
    {
        //1.将流程表数据转化成数组
        $data = $data->toArray();
        //2.获取主表数据
        $info = FuncMediatorInfo::where('id',$data['uid'])->first();
        $data['name'] = $info['name'];
        $data['zjbh'] = $info['zjbh'];
        $data['phone'] = $info['phone'];
        $data['open_time'] = $info['open_time'];
        //3.获取部门
        $dept = SysDept::where("id",$data['dept_id'])->first();
        $data['yyb_hs'] = $dept['yyb_hs'];
        $data['khfz_hs'] = $dept['khfz_hs'];
        //4.比例是否使用修改后的比例
        if($rate){
            $data['rate'] = $rate;
        }
        //5.判断是新签还是续签
        if(0 == $data['type']){
            $func = "relationMediator";
        }else{
            $func = "relationMediatorXQ";
        }
        //5.同步数据
        $post_data = [
            'type' => 'jjr',
            'action' => $func,
            'param' => [
                'info' => $data
            ]
        ];
        $result = $this->getCrmData($post_data);
        return $result;
    }

    /**
     * export
     */
    public function export(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 居间");
        $selectInfo = $this->get_params($request, ['id','status','flow_status','dept_id','mediator','remark','startTime','endTime']);

        $condition = $this->getPagingList($selectInfo, ['status'=>'=','dept_id'=>'=']);

        //居间人姓名或编号
        $mediator = $selectInfo['mediator'];
        if($mediator && is_numeric( $mediator )){
            array_push($condition, ['number','like',"%".$mediator."%"]);
        }elseif(!empty( $mediator )){
            array_push($condition, ['name','like',"%".$mediator."%"]);
        }

        //流程状态
        $ids = [];
        $where = [
            ['status',1],
            ['part_b_date','<>','']
        ];
        $flow_status = $selectInfo['flow_status'];
        if($flow_status){
            switch ($flow_status){
                case 1:
                    $where[] = ['is_check',0];
                    $where[] = ['is_back',0];
                    break;
                case 2:
                    $where[] = ['is_check',1];
                    $where[] = ['is_sure',0];
                    break;
                case 3:
                    $where[] = ['is_sure',1];
                    $where[] = ['is_handle',0];
                    break;
                case 4:
                    $where[] = ['is_handle',1];
                    break;
                case 5:
                    $where[] = ['is_back',1];
                    break;
                default:
                    break;
            }
        }

        //申请时间
        $start = $selectInfo['startTime'];
        $end = $selectInfo['endTime'];
        if($start){
            $where[] = ['part_b_date','>=',$start];
        }
        if($end){
            $where[] = ['part_b_date','<',$end];
        }
        if($selectInfo['remark']){
            $where[] = ['remark','like',"%".$selectInfo['remark']."%"];
        }

        $ids = FuncMediatorFlow::where($where)->orderBy('updated_at','desc')->pluck('uid')->toArray();
        $ids_ordered = implode(',',$ids);

        //设置需要导出的列，以及对应的表头
        $exportList = [
            'id' => 'ID',
            'dept_id' => '所属营业部',
            'name' => '居间人名称',
            'sex' => '性别',
            'manager_number' => '客户经理号',
            'number' => '居间编号',
            'phone' => '手机号',
            'open_time' => '开户日期',
            'xy_date_end' => '协议到期日',
            'zjbh' => '证件编号',
            'sfz_date_end' => '证件到期日',
            'sfz_address' => '证件地址',
            'address' => '联系地址',
            'profession' => '职业',
            'edu_background' => '学历',
            'rate' => '居间比例',
            'bank_name' => '银行名称',
            'bank_number' => '银行卡号',
            'bank_branch' => '支行名称',
        ];

        if(isset($selectInfo['id'])){
            $data = FuncMediatorInfo::whereIn('id', explode(',',$selectInfo['id']))->select(array_keys($exportList))->get()->toArray();
        }else{
            $data = FuncMediatorInfo::where($condition)->whereIn('id',$ids)->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->select(array_keys($exportList))->get()->toArray();
        }

        //增加列
        $exportList['bank'] = "银行信息";
        $exportList['part_b_date'] = "申请日期";
        $exportList['xy_date_begin'] = "协议开始日期";

        //设置表头
        $cellData[] = array_values($exportList);

        foreach($data as $k => $info){
            //银行信息
            $info['bank'] = $info['bank_name'].":".$info['bank_number'].$info['bank_branch'];
            
            //申请日期，取最新一条已完成履历
            $flow = FuncMediatorFlow::where([['uid',"=",$info['id']],['is_handle',"=","1"]])->orderBy('id','desc')->first();
            if($flow){
                $info['part_b_date'] = $flow->part_b_date;
                $info['xy_date_begin'] = $flow->xy_date_begin;
            }else{
                $info['part_b_date'] = "";
                $info['xy_date_begin'] = "";
            }

            $info['zjbh'] = $info['zjbh']."\t";
            $info['bank_number'] = $info['bank_number']."\t";
            
            //部门
            $dept = SysDept::where('id',$info['dept_id'])->first();
            $info['dept_id'] = $dept->name;

            array_push($cellData, array_values($info));
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 居间列表");
        Excel::create('居间列表',function($excel) use ($cellData){
            $excel->sheet('居间列表', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
