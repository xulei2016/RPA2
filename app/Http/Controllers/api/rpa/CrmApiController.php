<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Base\SysApiLog;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\Api\RpaHaLcztcx;

class CrmApiController extends BaseApiController
{
    private $redrect = "";//跳转

    public function __construct()
    {
        $redirect = $this->isredirect();
        $this->redrect = $redirect;
    }
    /**
     * 投资者密码接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function investor_password(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'filename' => 'required'
        ]);

        $file = $request->filename;

        // 打开csv文件
        $handle = fopen($file,"r");
        if(!$handle){
            $return = [
                'status' => 500,
                'msg' => "打开文件失败"
            ];

            //api日志
            $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

            return response()->json($return);
        }
        //解析csv文件
        $line = 0;
        while(!feof($handle)){
            $row = fgetcsv($handle);
            //跳过表头
            if($line == 0){
                $line++;
                continue;
            }
            //处理数据
            $csv_zjzh = iconv("gbk","utf-8",$row[2]);
            //crm获取交易编码
            $sql = "select * from dcuser.tfu_jybm_ss where khh = '".$csv_zjzh."'";
            $result = DB::connection('crm')->select($sql);

            if(isset($result[0]['JYBM']) && $result[0]['JYBM'] != ""){
                //有交易编码
                $has_jymb = 1;
            }else{
                //没有交易编码
                $has_jymb = 0;
            }
            $time = time();
            // 存入内部系统
            $sql = "insert into `oa_office_send` (`name`,`account`,`pwd`,`tel`,`has_jybm`,`fzjg`,`status`,`type`,`inputtime`,`content`) values ('{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$has_jymb}','{$row[5]}',2,'{$row[6]}','{$time}','')";

            $res = DB::connection('oa')->insert($sql);
            if($res){
                $return = [
                    'status' => $row[2]."*1",
                    'msg' => "数据录入成功"
                ];
            }else{
                $return = [
                    'status' => $row[2]."*0",
                    'msg' => "数据录入失败"
                ];
            }
            echo json_encode($return);
        }
        fclose($handle);
    }

    /**
     * 获取客户信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_info(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'type' => 'required|in:1,2,3,4,5',
            'date' => 'date_format:Y-m-d'
        ]);

        switch($request->type){
//        获取OA系统线上非特殊客户开户信息
            case 1:
                $date = isset($request->date) ? $request->date : date("Y-m-d");
                $sql = "select * from oa_customer_manager where special = '' and add_time >= '".$date."' and add_time < '".date('Y-m-d',strtotime('+1day',strtotime($date)))."'";
                $result = DB::connection("oa")->select($sql);
                break;
//        获取线下户
            case 2:
                //oa
                $date = isset($request->date) ? $request->date : date("Y-m-d");
                $sql = "select * from oa_customer_manager where special != '' and add_time >= '".$date."' and add_time < '".date('Y-m-d',strtotime('+1day',strtotime($date)))."'";
                $xs = DB::connection("oa")->select($sql);
                //crm
                $date = isset($request->date) ? date("Ymd",strtotime($request->date)) : date("Ymd");
                $sql = "select * from dcuser.tfu_khxx where KHRQ >= '".$date."' and KHRQ < '".date('Ymd',strtotime('+1day',strtotime($date)))."'";
                $all = DB::connection("crm")->select($sql);

                $xsList = [];
                foreach($xs as $list){
                    $xsList[] = $list->fundsNum;
                }

                $xxList = [];
                foreach($all as $list){
                    if(!in_array($list->khh, $xsList)){
                        $xxList[] = $list;
                    }
                }
                $result = $xxList;
                break;
//        获取OA系统特殊客户开户信息
            case 3:
                $date = isset($request->date) ? $request->date : date("Y-m-d");
                $sql = "select * from oa_customer_manager where special != '' and add_time >= '".$date."' and add_time < '".date('Y-m-d',strtotime('+1day',strtotime($date)))."'";
                $result = DB::connection("oa")->select($sql);
                break;
//        获取crm系统即时全部客户开户信息
            case 4:
                $date = isset($request->date) ? date("Ymd",strtotime($request->date)) : date("Ymd");
                $sql = "select * from dcuser.tfu_khxx where KHRQ >= '".$date."' and KHRQ < '".date('Ymd',strtotime('+1day',strtotime($date)))."'";
                $result = DB::connection("crm")->select($sql);
                break;
//         获取OA系统线上全部客户开户信息
            case 5:
                $date = isset($request->date) ? $request->date : date("Y-m-d");
                $sql = "select * from oa_customer_manager where add_time >= '".$date."' and add_time < '".date('Y-m-d',strtotime('+1day',strtotime($date)))."'";
                $result = DB::connection("oa")->select($sql);
                break;
            default:
                $result = [];
                break;
        }
        $return = [
            'status' => 200,
            'msg' => $result
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 获取客户可用资金
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_kyzj(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'khh' => 'required|integer',
        ]);

        $sql = "select khh,KYZJ,RQ from dcuser.tfu_zjqkls where KHH = ".$request->khh." and rownum <= 5 order by RQ desc";
        $res = DB::connection("crm")->select($sql);
        if($res){
            $return = [
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => "未找到数据！"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
    }

    /**
     * 获取历史开户及其风险要素
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function open_history(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
            'name' => 'required'
        ]);

        $sql = "select a.KHXM,a.ZJBH,a.ZJZH,a.KHZT,a.KHRQ,a.XHRQ,a.FXYS,b.name from futures.TKHXX a left join lborganization b on a.YYB = b.id  where a.ZJBH='{$request->idCard}' and a.KHXM='{$request->name}' order by a.KHRQ desc";

        $results = DB::connection("crm")->select($sql);

        //风险对照表
        $fxdzb = [
            'Y' => '司法限制',
            'X' => '司法冻结',
            'T' => '资料待规范',
            'S' => '监管休眠',
            '5' => '普通户',
            '4' => '二级户',
            '3' => '授权代理户',
            '2' => '资料不齐户',
            '1' => '休眠户',
            '0' => '一对多户'
        ];
        foreach($results as $k=>$v){

            $str = "";
            if($v->fxys){
                $fxyss = str_split($v->fxys);
                foreach($fxyss as $fxys){
                    $str .= $fxdzb[$fxys].",";
                }
            }
            $results[$k]->fxys = trim($str,",");
        }


        $re = [
            'status' => 200,
            'data' => $results,
            'halfYearOld' => date("Ymd",strtotime("-6 month"))
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 开户同步数据信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync_data(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'jjr' => 'required',
            'fundsNum' => 'required|integer'
        ]);

        $data = [
            'yybName' => $request->yyb,
            'jjrNum' => $request->jjr,
            'name' => $request->name,
            'idCard' =>$request->idCard,
            'customerNum' =>$request->customerNum,
            'fundsNum' =>$request->fundsNum,
            'message' =>$request->message,
            'creater' =>$request->getClientIp(),
            'add_time' => date('Y-m-d h:i:s'),
            'special' => trim($request->special,','),
            'is_visit' => 0
        ];
        //判断是否强制提交，如果是强制提交。验证错误时返回状态为true
        $comellent_submit = isset($request->comellent_submit) ? $request->comellent_submit : "";
        if($comellent_submit){
            $status = 200;
        }else{
            $status = 500;
        }
        //判断是否已提交
        //判断资金账号
        $sql = "select * from dcuser.tfu_khxx where zjzh= ".$data['fundsNum'];
        $result = DB::connection("crm")->select($sql);

        if($result){
            //判断身份证号
            if($result[0]->zjbh != $data['idCard']){
                $re = [
                    'status' => 500,
                    'msg' => "该资金账号已被占用！"
                ];

                //api调用日志
                $log = [
                    'api' => __FUNCTION__,
                    'param' => $request,
                    'return' => response()->json($re),
                    'ip' => $request->getClientIp()
                ];
                SysApiLog::create($log);

                return response()->json($re);
            }
        }
        // 判断特殊开户是否存在
        $sql = "select * from oa_customer_manager where fundsNum = ".$data['fundsNum'];
        $res = DB::connection("oa")->select($sql);
        if($res){
            foreach($res as $v){
                $arr1 = explode(",",$v->special);
                $arr2 = explode(",",$data['special']);
                $same = array_intersect($arr1,$arr2);
                if(!empty($same)){
                    $re = [
                        'status' => $status,
                        'msg' => "同一个客户不能开相同的特殊品种！！"
                    ];

                    //api日志
                    $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

                    return response()->json($re);
                }
            }
        }
        //非特殊客户，crm待处理信息表是否有相同资金账号
        if(!$data['special']){
            $sql = "select * from futures.txctc_jjr_ygxxcl where zjzh={$data['fundsNum']} and CLZT != 4";
            $result = DB::connection("crm")->select($sql);
            if($result){
                $re = [
                    'status' => 500,
                    'msg' => "CRM系统已存在该客户，请检查后再试！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

                return response()->json($re);
            }
        }

        foreach($data as &$k){
            $k = addslashes($k);
        }

        //客户归属关系
        if('' != $data['customerNum'] && null != $data['customerNum']){
            //$manager = M('office_mmanager')->where('manager_number = '.$data['customerNum'])->find();
            $sql = "select * from oa_office_mmanager where manager_number =".$data['customerNum'];
            $manager = DB::connection("oa")->select($sql);

            if($manager){
                //客户经理
                $manager = $manager[0];
                $data['customerManagerName'] = $manager->manager_name;

                //营业部信息
                $sql = "select * from oa_dept where did =".$manager->pid;
                $yyb = DB::connection("oa")->select($sql);
                if($yyb){
                    $yyb = $yyb[0];
                    $data['yybNum'] = $manager->pid;
                    $data['yybName'] = $data['yybName'] ? $data['yybName'] : $yyb->deptname;
                }

                //居间人信息
                $sql = "select * from oa_mediator where number = " . $data['jjrNum'];
                $jjr = DB::connection("oa")->select($sql);
                if($jjr){
                    $jjr = $jjr[0];
                    $data['jjrName'] = $jjr->mediatorname;
                }
            }
        }else{
            //居间人信息
            $sql = "select * from oa_mediator where number = " . $data['jjrNum'];
            $jjr = DB::connection("oa")->select($sql);
            if($jjr){
                $jjr = $jjr[0];
                $data['jjrName'] = $jjr->mediatorname;
                $data['yybNum'] = $jjr->did;
                //营业部信息
                $sql = "select * from oa_dept where did =".$jjr->did;
                $yyb = DB::connection("oa")->select($sql);
                if($yyb){
                    $yyb = $yyb[0];
                    $data['yybName'] = $data['yybName'] ? $data['yybName'] : $yyb->deptname;
                }
            }
        }
        //写入内部系统
        $result1 = DB::connection("oa")->table("oa_customer_manager")->insertGetId($data);
        //写入rpa
        $result2 = DB::table("rpa_customer_manager")->insertGetId($data);
        if($result1 && $result2){
            //开户插件同步居间关系到crm系统
            //增加二次股指、激活、更新判断，以上客户不同步到crm
            if(!$data['special']){
                if(!$res = $this->relationCustomer($data)){
                    $re = [
                        'status' => 500,
                        'msg' => "CRM系统推送数据失败，请联系软件工程部！"
                    ];

                    //api日志
                    $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

                    return response()->json($re);
                };
            }

            $re = [
                'status' => 200,
                'msg' => "信息录入成功！"
            ];

        }else{

            $re = [
                'status' => 500,
                'msg' => "内部系统信息录入失败，请联系软件工程部！"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取客户关系
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_relation(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'account' => 'required|integer',
            'name' => 'required'
        ]);

        $sql = "select * from TKHXX where KHXM='".$request->name."' and ZJZH=".$request->account;

        $result = DB::connection("crm")->select($sql);
        if($result){
            //部门编号
            $yyb_number = $result[0]->yyb;

            //部门名称
            $yyb_name = $this->getDeptName($yyb_number);

            $id = $result[0]->id;

            $jjr = $this->getMediator($id);
            //居间人编号
            $jjr_number = $jjr['jjr_number'];
            //居间人姓名
            $jjr_name = $jjr['jjr_name'];

            $jlr = $this->getManagerById($id);
            //客户经理编号
            $jlr_number = $jlr['yg_number'];
            //客户经理姓名
            $jlr_name = $jlr['yg_name'];

            $re = [
                'status' => 200,
                'msg' => [
                    'yyb_number' => $yyb_number,
                    'yyb_name' => $yyb_name,
                    'jjr_number' => $jjr_number,
                    'jjr_name' => $jjr_name,
                    'jlr_number' => $jlr_number,
                    'jlr_name' => $jlr_name
                ]
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '该资金账号未找到'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取居间关系
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_mediator_relation(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'phone' => 'required|integer'
        ]);
        $sql = "select * from TXCTC_JJR where DH='".$request->phone."' OR LXSJ='".$request->phone."' order by id desc";
        $result = DB::connection("crm")->select($sql);
        if($result){
            $result = $result[0];
            $kfr = $result->kfr;
            if(empty($kfr)){
                $re = [
                    'status' => 500,
                    'msg' => '不是有效的居间'
                ];

                //api调用日志
                $log = [
                    'api' => __FUNCTION__,
                    'param' => $request,
                    'return' => response()->json($re),
                    'ip' => $request->getClientIp()
                ];
                SysApiLog::create($log);

                return response()->json($re);
            }
            //部门编号
            $yyb_number = $result->yyb;
            //部门名称
            $yyb_name = $this->getDeptName($yyb_number);

            //居间人编号
            $jjr_number = $result->bh;
            //居间人姓名
            $jjr_name = $result->xm;

            $jlr = $this->getManagerByKfr($kfr);
            //客户经理编号
            $jlr_number = $jlr['number'];
            //客户经理姓名
            $jlr_name = $jlr['name'];

            $re = [
                'status' => 200,
                'msg' => [
                    'yyb_number' => $yyb_number,
                    'yyb_name' => $yyb_name,
                    'jjr_number' => $jjr_number,
                    'jjr_name' => $jjr_name,
                    'jlr_number' => $jlr_number,
                    'jlr_name' => $jlr_name
                ]
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '该号码不是居间'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);

    }

    /**
     * 反洗钱插件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fxq(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'customernum' => 'required',
        ]);

        $sql = "select * from dcuser.tfu_khxx where KHH = ".$request->customernum;
        $result = DB::connection("crm")->select($sql);

        if(empty($result)){
            $re = [
                'status' => 500,
                'msg' => "未找到客户"
            ];

            //api调用日志
            $log = [
                'api' => __FUNCTION__,
                'param' => $request,
                'return' => response()->json($re),
                'ip' => $request->getClientIp()
            ];
            SysApiLog::create($log);

            return response()->json($re);
        }
        $result = $result[0];

        $zydm = [
            '1' => '文教科卫专业人员',
            '2' => '党政 ( 在职，离退休 ) 机关干部',
            '3' => '企事业单位干部',
            '4' => '行政企事业单位工人',
            '5' => '农民',
            '6' => '个体',
            '7' => '无业',
            '8' => '军人',
            '9' => '其他',
            'A' => '工程施工人员',
            'B' => '环境监测与废物处理人员',
            'C' => '检验、计量人员',
            'D' => '离退休人员',
            'E' => '专业投资者',
            'F' => '军人',
            'G' => '学生',
            'a' => '国家机关、党群组织、企业、事业单位负责人',
            'b' => '科学研究人员',
            'c' => '信息技术、工程技术、农业技术、卫生专业技术人员',
            'd' => '经济、金融业务人员',
            'e' => '法律专业人员',
            'f' => '教学人员，体育工作、新闻出版工作人员',
            'g' => '安全保卫和消防人员',
            'h' => '邮政和电信业务人员',
            'i' => '交通运输、购销、仓储人员',
            'j' => '餐饮、旅游服务人员',
            'k' => '医疗卫生辅助服务、社会服务和居民生活服务人员',
            'l' => '农、林、牧、渔、水利业生产人员',
            'm' => '勘探、矿物开采、金属冶炼、轧制人员',
            'n' => '机械制造加工、机械设备修理人员',
            'o' => '电子元器件、机电产品及电力设备制造、装配、调试及维修人员',
            'p' => '化工产品、橡胶及塑料制品生产人员',
            'q' => '印染、纺织、缝纫人员，皮革制品加工制作人员',
            'r' => '粮油、食品饮料、饲料生产加工人员',
            's' => '烟草及其制品加工人员、药品生产人员',
            't' => '木制品、纸制品、建筑材料、玻璃、陶瓷制品生产加工人员',
            'u' => '广播影视作品、工艺美术品、文化体育用品制作人员，文物保护作业人员',
            'v' => '文化工作、健身娱乐、珠宝业、博彩业、拍卖典当、艺术品收藏人员',
            'w' => '废品收购工作人员',
            'x' => '电子商务工作人员',
            'y' => '离岸公司、国际组织工作人员',
            'z' => '个体工商户、私营企业主'
        ];

        $xldm = [
            '1' => '博士',
            '2' => '硕士',
            '3' => '学士',
            '4' => '大专',
            '5' => '中专',
            '6' => '高中',
            '7' => '初中及其以下',
            '8' => '其他'
        ];

        $result->zymc = isset($zydm[$result->zydm]) ? $zydm[$result->zydm] : '-----未识别-----' ;

        $result->xlmc = isset($xldm[$result->xldm]) ? $xldm[$result->xldm] : '-----未识别-----' ;

        if(!isset($request->ie)){
            $re = [
                'status' => 200,
                'msg' => $result
            ];

            //api日志
            $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

            return response()->json($re);
        }

        $result->zymc = isset($zydm[$result->zydm]) ? $zydm[$result->zydm] : '<span style="color:red;">-----未识别-----</span>' ;
        $result->xlmc = isset($xldm[$result->xldm]) ? $xldm[$result->xldm] : '<span style="color:red;">-----未识别-----</span>' ;



        //////////////////////////////////////////////////////////下为网页版输出/////////////////////////////////////////////////////////////

        //模板输出
        $khfs = ($result->zy=='互联网开户扩展信息登记') ? '互联网' : '<span style="color:red;">未识别</span>' ;
        $age = $this->get_age($result->zjbh);

        //模板1
        $model['1'] = "<span style='color:red;'>——{$result->khxm}</span><br/>经调查把客户风险评估指标分为四类基本要素<br/>客户特性：客户持有效身份证明文件于{$result->khrq}通过{$khfs}方式开户，客户为普通投资者，身份证号码为{$result->zjbh}，年龄为{$age}岁，学历为{$result->xlmc}。<br/>客户地域：客户身份地址为：{$result->sfzdz}，联系地址为：{$result->dz}。<br/>客户业务：客户自己操作账户，不存在实际控制其他主体的期货交易或被其他主体实际控制期货交易的行为。<br/>客户行业：客户职业为{$result->zymc}，客户本人不是外国政要，与洗钱、职务犯罪等的关联性不高。<br/>根据公司的规章制度，并结合对客户的尽职调查，现将此客户的风险等级评定为低风险。";

        //模板2
        $model['2'] = "经调查把客户风险评估指标分为四类基本要素<br/>客户特性：客户持有效身份证明文件于{$result->khrq}通过{$khfs}方式开户，客户为普通投资者，客户关联XX银行和XX银行,身份证号码为{$result->zjbh}，年龄为{$age}岁，学历为{$result->xlmc}。<br/>客户地域：客户身份地址为：{$result->sfzdz}，联系地址为：{$result->dz}。<br/>客户业务：客户自己操作账户，不存在实际控制其他主体的期货交易或被其他主体实际控制期货交易的行为。<br/>客户行业：客户职业为{$result->zymc}，客户本人不是外国政要，与洗钱、职务犯罪等的关联性不高。<br/>根据公司的规章制度，并结合对客户的尽职调查，现将此客户的风险等级评定为低风险。";

        //模板3
        $model['3'] = "经调查把客户风险评估指标分为四类基本要素<br/>客户特性：客户持有效身份证明文件于{$result->khrq}通过{$khfs}方式开户，客户为普通投资者，身份证号码为{$result->zjbh}，年龄为{$age}岁，学历为{$result->xlmc}。<br/>客户地域：客户身份地址为：{$result->sfzdz}，联系地址为：{$result->dz}。<br/>客户业务：客户自己操作账户，不存在实际控制其他主体的期货交易或被其他主体实际控制期货交易的行为。<br/>客户行业：客户职业为{$result->zymc}，客户本人不是外国政要，与洗钱、职务犯罪等的关联性不高。客户身份证有效期为{$result->zjjsrq}，我司已跟客户提示，要求其提供最新有效身份证,根据公司的规章制度，并结合对客户的尽职调查，现将此客户的风险等级评定为低风险。";


        $re = [
            'status' => 200,
            'msg' => $model
        ];

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($re),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 居间人流程同步
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediator_flow(Request $request)
    {
        //跳转检测
        $res = $this->change($request->getRequestUri(),$request->all());
        if($res !== true){
            return response()->json($res);
        }
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|integer',
        ]);

        $lc = RpaHaLcztcx::find($request->id);
        if($lc){
            $return = $this->jjrDistribute($lc);
        }else{
            $return = [
                'status' => 500,
                'msg' => "未找到流程"
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,response()->json($return),$request->getClientIp());

        return response()->json($return);
    }

    /**************************************客户关系业务处理**********************************/
    /**
     * 根据营业部编号获得名称
     * @param $yyb_number
     * @return mixed
     */
    private function getDeptName($yyb_number){
        $sql = "select NAME from LBORGANIZATION where id=".$yyb_number." order by id desc";
        $result = DB::connection("crm")->select($sql);
        return $result[0]->name;
    }

    /**
     * 根据ID找居间人信息
     * @param $id
     * @return array
     */
    private function getMediator($id){
        $re = [
            'jjr_number' => "",
            'jjr_name' => ""
        ];

        $sql = "select GXR from txctc_jjrkhgx where khh=".$id." order by id desc";
        $result = DB::connection("crm")->select($sql);
        if($result){
            $gxr = $result[0]->gxr;
            $sql = "select * from TXCTC_JJR WHERE ID =".$gxr." order by id desc";
            $result = DB::connection("crm")->select($sql);
            if($result){
                $re = [
                    'jjr_number' => $result[0]->bh,
                    'jjr_name' => $result[0]->xm,
                ];
            }
        }
        return $re;
    }

    /**
     * 根据id获取经理人信息
     * @param $id
     * @return array
     */
    private function getManagerById($id){
        $re = [
            'yg_number' => "",
            'yg_name' => "",
        ];

        $sql = "select GXR from txctc_ygkhgx where khh=".$id." order by id desc";
        $result = DB::connection("crm")->select($sql);
        if($result){
            $gxr = $result[0]->gxr;
            $sql = "select * from TXCTC_YGXX WHERE ID =".$gxr." order by id desc";
            $result = DB::connection("crm")->select($sql);
            if($result){
                $re = [
                    'yg_number' => $result[0]->bh,
                    'yg_name' => $result[0]->xm,
                ];
            }
        }
        return $re;
    }

    /**
     * 根据kfr获取经理人信息
     * @param $kfr
     * @return array
     */
    private function getManagerByKfr($kfr){
        $re = [
            'name' => "",
            'number' => ""
        ];

        $sql = "select * from TXCTC_YGXX where id=".$kfr." order by id desc";
        $result = DB::connection("crm")->select($sql);
        if($result){
            $re = [
                'name' => $result[0]->xm,
                'number' => $result[0]->bh
            ];
        }
        return $re;
    }

    /**************************************居间信息处理业务**********************************/

    /**
     * 居间人业务分发
     */
    private function jjrDistribute($lc){
        if($lc->state >= 4){
            switch($lc->tablename){
                case "TXCTC_LC_JJR_XZ":  //居间人新增流程
                    $re = $this->jjrAdd($lc->instid);
                    break;
                case "TXCTC_LC_JJR_XG": //居间人信息变更流程
                    $re =  $this->jjrChange($lc->instid);
                    break;
                case "TXCTC_LC_JJR_XQSQ": //居间人续签流程
                    $re =  $this->jjrXQ($lc->instid);
                    break;
                default:
                    $re = [
                        'status' => 500,
                        'msg' => "该流程不是居间新增。变更。续签流程！"
                    ];
                    break;
            }

        }else{
            $re = [
                'status' => 500,
                'msg' => "该流程还未完成"
            ];
        }
        return $re;
    }


    /**
     * 居间新增流程
     * @param $instid
     * @return array
     */
    private function jjrAdd($instid){
        $table = 'TXCTC_LC_JJR_XZ';
        //调用接口，保存文件
        $postdata = array(
            "instid"=>$instid,
            'tabname'=>$table
        );
        //保存文件
        $savepath = $this->save_file($postdata);
        if($savepath === false){
            $re = [
                'status' => 500,
                'msg' => "新增流程{$instid}附件保存失败"
            ];
            return $re;
        }

        $data = $this->dataHandle($table,$instid,$savepath);
        //数据补充
        $data['uid'] = 0;
        $data['employeeid'] = 0;
        if($id = DB::connection("mysql_oa")->table("oa_mediator")->insertGetId($data)){
            //成功
            $re = [
                'status' => 200,
                'msg' => "新增流程{$instid},数据同步成功"
            ];
        }else{
            //失败
            $re = [
                'status' => 500,
                'msg' => "新增流程{$instid},数据同步失败"
            ];
        }
        return $re;
    }

    /**
     * 居间变更流程
     * @param $instid
     * @return array
     */
    private function jjrChange($instid){
        //调用接口，保存文件
        $table = 'TXCTC_LC_JJR_XG';
        $postdata = array(
            "instid"=>$instid,
            'tabname'=>$table
        );

        //保存文件
        $savepath = $this->save_file($postdata);
        if($savepath === false){
            $re = [
                'status' => 500,
                'msg' => "修改流程{$instid}附件保存失败"
            ];
            return $re;
        }

        //同步数据处理
        $data = $this->dataHandle($table,$instid,$savepath);
        if($savepath['have']){
            //更新
            $mid = DB::connection("oa")->table("oa_mediator")->where("id",$savepath['have'])->update($data);
            if($mid){
                //重新生成协议
                if($savepath['uid']){
                    $post_data = [
                        'uid' => $savepath['uid'],
                        'path' =>$savepath['path']
                    ];
                    $this->xyHB($post_data);
                }
                $re = [
                    'status' => 200,
                    'msg' => "修改流程{$instid},数据同步成功"
                ];
            }else{
                $re = [
                    'status' => 500,
                    'msg' => "修改流程{$instid},数据同步失败"
                ];
            }
        }else{
            //新增

            //数据补充
            $data['uid'] = 0;
            $data['employeeid'] = 0;
            if($id = DB::connection("oa")->table("oa_mediator")->inserGetId($data)){
                //成功
                $re = [
                    'status' => 200,
                    'msg' => "修改流程{$instid},数据同步成功"
                ];
            }else{
                //失败
                $re = [
                    'status' => 500,
                    'msg' => "修改流程{$instid},数据同步失败"
                ];
            }
        }
        return $re;
    }

    /**
     * 居间续签流程
     * @param $instid
     * @return array
     */
    private function jjrXQ($instid){
        $table = 'TXCTC_LC_JJR_XQSQ';
        $postdata = array(
            "instid"=>$instid,
            'tabname'=>$table
        );
        //保存文件
        $savepath = $this->save_file($postdata);
        if($savepath === false){
            $re = [
                'status' => 500,
                'msg' => "续签流程{$instid}附件保存失败"
            ];
            return $re;
        }
        //同步数据处理
        $data = $this->dataHandle($table,$instid,$savepath,true);

        //线下居间更新信息
        if(!$savepath['uid']){
            if($savepath['have']){
                //更新
                $mid = DB::conection("crm")->table("oa_mediator")->where("id",$savepath['have'])->update($data);
                if($mid){
                    //重新生成协议
                    if($savepath['uid']){
                        $post_data = [
                            'uid' => $savepath['uid'],
                            'path' => $savepath['path']
                        ];
                        $this->xyHB($post_data);
                    }
                    $re = [
                        'status' => 200,
                        'msg' => "续签流程{$instid},数据同步成功"
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'msg' => "续签流程{$instid},数据同步失败"
                    ];
                }
            }else{
                //新增

                //数据补充
                $data['uid'] = 0;
                $data['employeeid'] = 0;
                if($id = DB::conection("mysql_oa")->table("oa_mediator")->inserGetId($data)){
                    //成功
                    $re = [
                        'status' => 200,
                        'msg' => "续签流程{$instid},数据同步成功"
                    ];
                }else{
                    //失败
                    $re = [
                        'status' => 500,
                        'msg' => "续签流程{$instid},数据同步失败"
                    ];
                }
            }
        }else{
            $re = [
                'status' => 200,
                'msg' => "续签流程{$instid},线上续签不做处理"
            ];
        }
        return $re;
    }

    /**
     * 同步数据处理
     * @param $table
     * @param $instid
     * @param array $savepath
     * @param bool $xq
     * @return array
     */
    public function dataHandle($table,$instid,$savepath=array(),$xq=false){

        //获取居间人信息,同步到内部系统
        $sql = "select * from TXCTC_JJR where SFZH = '".$savepath['SFZH']."'";
        $jjr = DB::connection("crm")->select($sql);

        //数据处理
        //1.部门
        $sql = "select name from lborganization where id = {$jjr[0]->yyb}";
        $yyb = DB::connection("crm")->select($sql);

        $sql = "select did from oa_dept where deptname='{$yyb[0]->name}'";
        $row = DB::connection("oa")->select($sql);
        $row = $row[0];

        //2.性别
        $sql = "select CBM,NOTE from txtdm where FLDM = 'SEX'";
        $xb = DB::connection("crm")->select($sql);
        $sex = "";
        foreach($xb as $v){
            if($jjr[0]->xb == $v->cbm){
                $sex = $v->note;
            }
        }
        //3.学历[1初中2高中3中专4专科5本科6硕士7博士8其他]
        switch($jjr[0]->xl){
            case 1:
                $edu = "初中";
                break;
            case 2:
                $edu = "高中";
                break;
            case 3:
                $edu = "中专";
                break;
            case 4:
                $edu = "专科";
                break;
            case 5:
                $edu = "本科";
                break;
            case 6:
                $edu = "硕士";
                break;
            case 7:
                $edu = "博士";
                break;
            default:
                $edu = "其他";
                break;

        };
        //4.开户银行
        $sql = "select CBM,NOTE from txtdm where FLDM ='JSYH'";
        $yh = DB::connection("crm")->select($sql);
        $bank="其他";
        foreach($yh as $v){
            if($jjr[0]->khyh == $v->cbm){
                $bank = $v->note;
            }
        }
        //5.经理号
        $sql = "select BH from TXCTC_YGXX where ID={$jjr[0]->kfr}";
        $manager = DB::connection("crm")->select($sql);
        //6.比例
        $sql = "select a.FAMC from TXCTC_TCFA_JJR a left join TXCTC_JJR_TCFA b on a.ID=b.TCFA where b.TXCTC_JJR_ID = {$jjr[0]->id}";
        $rate = DB::connection("crm")->select($sql);

        $data = [
            'did' => $row['did'],
            'mediatorname' => $jjr[0]->xm,
            'brith' => $this->set_date_format(substr($jjr[0]->sfzh,6,8)),
            'tel' => isset($jjr[0]->lxsj) ? $jjr[0]->lxsj : $jjr[0]->dh,
            'email' => $jjr[0]->email,
            'sex' => $sex,
            'edubackground' => $edu,
            'address' => $jjr[0]->lxdz,
            'xy_date_begin' =>  $this->set_date_format($jjr[0]->xykssj),
            'xy_date_end' => $this->set_date_format($jjr[0]->xyjssj),
            'sfz_number' => $jjr[0]->sfzh,
            'sfz_date_end' => $this->set_date_format($jjr[0]->zjdq) ,
            'sfz_address' => $jjr[0]->jzdz,
            'bank_number' => $jjr[0]->yhzh,
            'bank_name' => $bank,
            'bank_branch' => $jjr[0]->fhmc,
            'bank_accountname' => $jjr[0]->xm,

            'step' => 8,
            'postalcode' => $jjr[0]->yzbm,
            'managerNo' => $manager[0]->bh,
            'profession' => $jjr[0]->zy,
            'rate' => $rate[0]->famc,
            'isCheck' => 1,
            'isFirst' => 1,
            'isHandle' => 1,
            'open_date' => $this->set_date_format($jjr[0]->khrq),
            'updated' => date("Y-m-d H:i:s",time()),
            'jrrate' => '0.00',
            'number' => $jjr[0]->bh,
        ];
        if(!empty($savepath['TX2'])){
            $data['sfz_img_zm'] = $savepath['TX2'];
        }
        if(!empty($savepath['TX3'])){
            $data['sfz_img_fm'] = $savepath['TX3'];
        }
        if(!empty($savepath['TX5'])){
            $data['sfz_img_sc'] = $savepath['TX5'];
        }
        if(!empty($savepath['TX15'])){
            $data['bank_img'] = $savepath['TX15'];
        }
        if(!empty($savepath['TX1'])){
            $data['TX1'] = $savepath['TX1'];
        }
        if(!empty($savepath['TX4'])){
            $data['TX4'] = $savepath['TX4'];
        }
        if(!empty($savepath['TX6'])){
            $data['TX6'] = $savepath['TX6'];
        }
        if(!empty($savepath['TX7'])){
            $data['TX7'] = $savepath['TX7'];
        }
        if(!empty($savepath['TX8'])){
            $data['TX8'] = $savepath['TX9'];
        }
        if(!empty($savepath['TX9'])){
            $data['TX9'] = $savepath['TX9'];
        }
        if(!empty($savepath['TX10'])){
            $data['TX10'] = $savepath['TX10'];
        }
        if(!empty($savepath['TX11'])){
            $data['TX11'] = $savepath['TX11'];
        }
        if(!empty($savepath['TX12'])){
            $data['TX12'] = $savepath['TX12'];
        }
        if(!empty($savepath['TX13'])){
            $data['TX13'] = $savepath['TX13'];
        }
        if(!empty($savepath['TX14'])){
            $data['TX14'] = $savepath['TX14'];
        }
        if(!empty($jjr[0]['BZ'])){
            $data['remark'] = $jjr[0]['BZ'];
        }
        if($xq) $data['isRenew'] = 1;
        return $data;
    }

    /**
     * 保存文件
     * @param $postdata
     * @return bool|mixed
     */
    public function save_file($postdata){
        $guzzle = new Client();
        $response = $guzzle->post('http://172.16.253.26/interface/yxxt/yxxt.php',[
            'form_params' => $postdata,
        ]);
        $body = $response->getBody();
        $savepath = json_decode((String)$body,true);
        if(!$savepath){
            return false;
        }else{
            return $savepath;
        }
    }

    /**
     * 生成协议
     * @param $post_data
     * @return mixed|\Psr\Http\Message\StreamInterface
     */
    public function xyHB($post_data)
    {
        $url = 'http:/172.16.191.26/oa2/index.php?m=Xy&a=xyHB2';
        $guzzle = new Client();
        $response = $guzzle->post($url,[
            'form_params' => $post_data,
        ]);
        $body = $response->getBody();
        $body = json_decode((String)$body,true);

        return $body;
    }

    /**************************工具方法*************************************/

    /**
     *  根据身份证号码计算年龄
     *  @param string $idcard    身份证号码
     *  @return int $age
     */
    public function get_age($idcard){
        if(empty($idcard)) {
            return null;
        }
        //  获得出生年月日的时间戳
        $date = strtotime(substr($idcard,6,8));
        //  获得今日的时间戳
        $today = strtotime('today');
        //  得到两个日期相差的大体年数
        $diff = floor(($today-$date)/86400/365);
        //  strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
        $age = strtotime(substr($idcard,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;
        return $age;
    }

    /**
     * 修改日期格式
     * @param $time
     * @return string
     */
    public function set_date_format($time){
        return substr($time,0,4)."-".substr($time,4,2)."-".substr($time,-2);
    }

    /**
     * 开户同步数据信息
     * @param $info
     * @return mixed
     */
    public function relationCustomer($info){
        $data = [
            'id' => '',
            'rq' => date('Ymd',time()),
            'zjzh' => $info['fundsNum'],
            'yyb' => '',
            'khxm' => $info['name'],
            'zjhm' => $info['idCard'],
            'jjrbh' => $info['jjrNum'],
            'ygbh' => $info['customerNum'],
            'bz' => '内部系统推送标记',//$info['message']

            'YYB_DC' => '',
            'DKHTCFA' => '',
            'CLZT' => '',
            'CZR' => '',
            'CZRQ' => date('Ymd',time()),

            'jjr' => '',
            'yg' => '',
            'jjrxm' => '',
            'ygxm' => '',
            'xgbz' => '',
            'hfbz' => '',
            'ygxgbz' => '',
            'khfz_hs' => '',
            'lczt' => '',
        ];

        $result = DB::connection("crm")->table("futures.txctc_jjr_ygxxcl")->insertGetId($data);
        return $result;
    }

    /**
     * 接口是否需要转移
     * @return string
     */
    private function isredirect()
    {
        $redrect = "";
        $server = $this->get_config();
        $config = $this->get_config(['S1_inner','S2_inner','rpa_clock_interval']);
        //判断当前服务器是否是主服务器
        if($server == "H1_inner" || $server == "H2_inner"){
            //判断从服务器是否正常工作
            $cards = $this->getCard();
            foreach($cards['msg'] as $card){
                // 从一
                if($card['host'] == 'S1_inner' && (time() - $card['created_at'] < $config['rpa_clock_interval']*2)){
                    $redrect = $config['S1_inner'];
                    return $redrect;
                }
                //从二
                if($card['host'] == 'S2_inner' && (time() - $card['created_at'] < $config['rpa_clock_interval']*2)){
                    $redrect = $config['S2_inner'];
                    return $redrect;
                }
            }
        }
        return $redrect;
    }

    /**
     * 接口转移
     * @param $url
     * @param $data
     * @return \Psr\Http\Message\StreamInterface|string
     */
    private function change($url,$data){
        if($this->redrect != ""){
            //主机名
            $host = "http://".$this->redrect.":8088";
            //获取token
            $token = $this->access_token($host);
            $guzzle = new Client();
            $response = $guzzle->post($host.$url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => $token
                ],
                'form_params' => [
                    $data
                ],
            ]);
            $body = $response->getBody();
            $body = json_decode((string)$body,true);

            return $body;
        }
        return true;
    }

}
