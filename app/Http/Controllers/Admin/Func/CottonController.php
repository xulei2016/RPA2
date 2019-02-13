<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Func\rpa_cotton_entrys;
use App\Models\Admin\Func\rpa_cotton_entrys_tmps;
use App\Models\Admin\Rpa\rpa_immedtasks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * JJRVisController
 * @author hsu lay
 */
class CottonController extends BaseAdminController{
    //查询页展示
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 仓单临时数据 页");
        //获取结算部名单
        $conditions = [["groupID","=",2]];
        $result = SysAdmin::where($conditions)->get();
        return view('admin/func/Cotton/index', ['list' => $result]);
    }
    //归档页展示
    public function official(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 仓单归档数据 页");
        return view('admin/func/Cotton/official');
    }
    //查询信息
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request,["type","operator","state","from_saveDate","to_saveDate","customer"]);
        $type = $selectInfo['type'];
        if($type == "tmp"){
            $condition = $this->getPagingList($selectInfo, ['operator'=>'=','state'=>'=']);
        }elseif($type == "official"){
            $condition = $this->getPagingList($selectInfo, ['from_saveDate'=>'>=','to_saveDate'=>'<=']);
        }
        $customer = $selectInfo['customer'];
        if($customer && is_numeric( $customer )){
            array_push($condition,  array('khbm', '=', $customer));
        }elseif(!empty( $customer )){
            array_push($condition,  array('khmc', '=', $customer));
        }
        //预报总数
        $sum = rpa_cotton_entrys::where($condition)->sum('ybsl');
        $rows = $request->rows;
        if($type == "tmp"){
            return rpa_cotton_entrys_tmps::where($condition)->paginate($rows);
        }elseif($type == "official"){
            $rows = $request->rows;
            $data = rpa_cotton_entrys::where($condition)->paginate($rows);
            foreach($data as $k=>$v){
                $v['sum'] = $sum;
                $data[$k] = $v;
            }
            return $data;
        }
    }
    //添加页面
    public function add()
    {
        return view('admin/func/Cotton/add');
    }
    //添加
    public function adddata(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "上传 仓单数据");
        $fileResource = 'D:/uploadFile/zhxt/';
        $data = $_POST;
        $file = $_FILES['filename'];

        //文件上传
        if(isset($data) && isset($file['name']) && $file_address = $this->file_upload($fileResource,$file)){
            //数据接收处理
            if($file_address){
                $res = $this->saveExcel($file_address);
                if($res){
                    $info = ['code' => 200, 'info' => '文件上传成功。'];
                    echo json_encode($info);exit;
                };
            }
            $info = ['code' => 500, 'info' => '数据保存失败!'];
            echo json_encode($info);exit;
        }
        $info = ['code' => 500, 'info' => '文件上传上传失败!'];
        echo json_encode($info);exit;

    }
    //文件上传
    public function file_upload($fileResource,$file){
        $filename = $file['name'];

        //文件后缀识别判断
        $except = [
            'xls',
            'xlsx',
            'et',
            'ett',
            'xlt'
        ];
        $file_extension = explode('.', $filename);//文件后缀
        $file_end = end($file_extension);
        $file_extension = strtolower($file_end);//后缀名小写

        if(!in_array($file_end, $except)){
            return false;
        }

        //存储文件路径格式 日期/文件.excel/xlsx/et/ett/xlt
        $data = date('Ymd', time());
        $time = date('YmdHis',time());
        $folder = $fileResource.$data.'/';
        is_dir($folder) OR mkdir($folder, 0777, true);
        $new_file = $folder.$this->random(4).$time.'.'.$file_extension;

        //保存文件
        $result = $this->saveFile($file["tmp_name"], $new_file);
        if($result){
            return $new_file;
        }
        return false;
    }
    //excel参数保存
    public function saveExcel($file_address){
        //获取ip
        $info['ip'] = $this->getIp();
        //获取文件地址
        $info['file_address'] = $file_address;
        //添加时间
        $info['created_at'] = date('Y-m-d H:i:s', time());
        //操作人
        $info['operator'] = session('sys_admin')['realName'];

        $res = rpa_cotton_entrys_tmps::create($info);
        if($res){
            return true;
        }else{
            return false;
        }
    }
    // 生成随机文件名函数
    public function random($length){
        $captchaSource = "0123456789abcdefghijklmnopqrstuvwxyz这是一个随机打印输出字符串的例子";
        $captchaResult = ""; // 随机数返回值
        $captchaSentry = ""; // 随机数中间变量
        for($i=0;$i<$length;$i++){
            $n = rand(0, 35); #strlen($captchaSource));
            if($n >= 36){
                $n = 36 + ceil(($n-36)/3) * 3;
                $captchaResult .= substr($captchaSource, $n, 3);
            }else{
                $captchaResult .= substr($captchaSource, $n, 1);
            }
        }
        return $captchaResult;
    }
    //文件保存
    public function saveFile($file, $fileResource){
        $result = move_uploaded_file($file, $fileResource);
        return $result;
    }
    //获得真实ip
    public function getIp(){
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
    //详情
    public function detail(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 仓单详情");
        $info = rpa_cotton_entrys_tmps::find($id);
        $batch = DB::table('rpa_cotton_batchs_tmp')->where("eid",$id)->get();
        return view('admin/func/Cotton/detail', ['info' => $info,'batch'=>$batch]);
    }
    //详情
    public function official_detail(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 归档仓单详情");
        $info = rpa_cotton_entrys::find($id);
        $info = $info['data'];

        $batch = DB::table('rpa_cotton_batchs')->where("eid",$id)->get();
        return view('admin/func/Cotton/detail', ['info' => $info,'batch'=>$batch]);
    }
    //批量归档
    public function save(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "批量归档 仓单数据");
        $ids = $request->id;
        $data = explode(',',$ids);
        foreach($data as $id){
            $info = rpa_cotton_entrys_tmps::find($id)->toArray();
            unset($info['id']);
            unset($info['state']);
            unset($info['remark']);
            $info['saveDate'] = date("Y-m-d H:i:s",time());
            $batch = DB::table('rpa_cotton_batchs_tmp')->select("pihao","package","level")->where("eid",$id)->get()->map(function ($value) {return (array)$value;})->toArray();
            $eid = DB::table('rpa_cotton_entrys')->insertGetId($info);
            foreach($batch as $k=>$v){
                $v['eid'] = $eid;
                $v['time'] = time();
                $batch[$k] = $v;
                DB::table('rpa_cotton_batchs')->insert($v);
            }
            DB::table('rpa_cotton_batchs_tmp')->where("eid",$id)->delete();
        }
        rpa_cotton_entrys_tmps::destroy($data);
        return $this->ajax_return(200, '操作成功！');
    }
    //替包
    public function changePack(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "替包 仓单数据");
        $id = $request->id;
        $batchs = DB::table("rpa_cotton_batchs_tmp")->where('eid','=',$id)->get()->map(function ($value) {return (array)$value;})->toArray();
        foreach($batchs as $k=>$v){
            $eid = DB::table("rpa_cotton_batchs")->where('pihao','=',$v['pihao'])->value("eid");
            DB::table("rpa_cotton_batchs")->where('pihao','=',$v['pihao'])->update(["state"=>1]);
            $v['eid'] = $eid;
            $v['time'] = time();
            unset($v['id']);
            unset($v['remark']);
            DB::table("rpa_cotton_batchs")->insert($v);
        }
        DB::table("rpa_cotton_batchs_tmp")->where('eid','=',$id)->delete();
        rpa_cotton_entrys_tmps::destroy($id);
        return $this->ajax_return(200, '操作成功！');
    }
    //删除
    public function delete(Request $request){
        $ids = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 仓单 列表");
        $data = explode(',',$ids);
        foreach($data as $v){
            DB::table('rpa_cotton_batchs_tmp')->where('eid','=',$v)->delete();
            $res = rpa_cotton_entrys_tmps::find($v);
            if(is_file($res->file_address)){
                unlink($res->file_address);
            }
        }
        rpa_cotton_entrys_tmps::destroy($data);
        return $this->ajax_return(200, '操作成功！');
    }
    //发布任务
    public function immedtask(){
        $data = ['name'=>'AnalysisCottonExcel','jsondata'=>''];
        rpa_immedtasks::create($data);
        return $this->ajax_return(200, '操作成功！');
    }
    //是否解析完成
    public function isanalysis()
    {
        $condition = [
            ["state","=",0]
        ];
        $data = rpa_cotton_entrys_tmps::where($condition)->get();
        return $data;

    }
    //验证数据是否正确
    public function checkdata()
    {
        $res = DB::table('rpa_cotton_batchs_tmp')->pluck("pihao")->toArray();
        $unique_arr = array_unique($res);
        $repeat_arr = array_diff_assoc($res,$unique_arr);
        if($repeat_arr){
            //临时表查询
            foreach($repeat_arr as $v){
                DB::table('rpa_cotton_batchs_tmp')->where("pihao",$v)->update(['remark'=>"临时表有相同批号"]);
                $ids = DB::table('rpa_cotton_batchs_tmp')->where("pihao","=",$v)->pluck("eid")->toArray();
                foreach($ids as $id){
                    rpa_cotton_entrys_tmps::where('id',$id)->update(['state'=>2]);
                }
            }
        }
        //正式表查询
        foreach($res as $v){
            $where = [
                ["pihao","=",$v],
                ["state","=",0]
            ];
            $re = DB::table('rpa_cotton_batchs')->where($where)->get();
            if(!$re->isEmpty()){
                DB::table('rpa_cotton_batchs_tmp')->where("pihao",$v)->update(['remark'=>"正式表有相同批号"]);
                $ids = DB::table('rpa_cotton_batchs_tmp')->where("pihao","=",$v)->pluck("eid")->toArray();
                foreach($ids as $id){
                    rpa_cotton_entrys_tmps::where("id",$id)->update(['state'=>3]);
                }
            }
        }
        $r = [
            'code' => 200,
            'data' => "数据检验完成"
        ];
        echo json_encode($r);exit;

    }
    //excel模板下载
    public function download(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "下载 仓单文件");
        if($request->id){
            $data = rpa_cotton_entrys::find($request->id);
            $data = $data['data'];
            $file = $data->file_address;
        }else{
            $file_name = "郑商所棉花交割入库预报表.xls";     //下载文件名
            $file_name = iconv("utf-8","gbk",$file_name);
            $file_dir = "D:/download/";        //下载文件存放目录
            $file = $file_dir . $file_name;
        }

        //检查文件是否存在
        if (!file_exists($file)) {
            header("HTTP/1.0 404 Not Found");
            header("Status: 404 Not Found");
        } else {
            header('content-type:application/octet-stream');
            header('content-disposition:attachment; filename='.basename($file));
            header('content-length:'.filesize($file));
            readfile($file);
        }
    }
}
