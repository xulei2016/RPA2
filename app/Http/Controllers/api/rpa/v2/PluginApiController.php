<?php

namespace App\Http\Controllers\api\rpa\v2;

use App\Http\Controllers\api\BaseApiController;
use App\Models\Admin\Api\RpaShixincfa;
use App\Models\Admin\Api\RpaShixinsf;
use App\Models\Admin\Api\RpaShixinhss;
use App\Models\Admin\Api\RpaShixinxyzg;
use App\Models\Admin\Func\rpa_customer_manager;
use App\Models\Admin\Func\RpaCustomerSecondFinance;
use App\Models\Admin\Rpa\rpa_immedtasks;
use App\Models\Admin\Api\RpaCustomerInfo;
use App\Models\Admin\Api\RpaPreOpenAccount;
use App\Models\Admin\Rpa\rpa_timetasks;
use App\Models\Admin\Rpa\RpaBankRelationTmp;
use App\Models\Index\Mediator\FuncMediatorInfo;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PluginApiController extends BaseApiController
{
    /**
     * 失信查询
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function credit(Request $request)
    {
        // //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
            'name' => 'required',
            'type' => 'required|in:1,2'
        ]);

        $today = date("Y-m-d H:i:s",time());
        $yesterday = date("Y-m-d",strtotime("-1 day"))." 15:00:00";
        //期货
        $cfa = RpaShixincfa::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //证券
        $sf = RpaShixinsf::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //金融
        $jr = RpaShixinhss::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();

        //$type 1代表发布任务 2代表查询结果
        if($request->type == 1){
            $param = [
                'name' => $request->name,
                'idCard' => $request->idCard,
                'operator' => Auth::user()->name
            ];
            //期货
            if(!isset($cfa) || $cfa->state == -1){
                $data1 = [
                    'name' => 'SupervisionCFA_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res1 = rpa_immedtasks::create($data1);
            }
            //证券
            if(!isset($sf) || $sf->state == -1){
                $data2 = [
                    'name' => 'SupervisionSF_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res2 = rpa_immedtasks::create($data2);
            }
            //金融
            if(!isset($jr) || $jr->state == -1){
                $data3 = [
                    'name' => 'SupervisionHS_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res3 = rpa_immedtasks::create($data3);
            }

            $re = [
                'status' => 200,
                'msg' => 'rpa任务发布成功！'
            ];
        }else{
            if(!isset($cfa) || !isset($sf) || !isset($jr)){
                $re = [
                    'status' => 500,
                    'msg' => "未找到数据！"
                ];
            }elseif($cfa->state == null || $sf->state == null || $jr->state == null){
                $re = [
                    'status' => 500,
                    'msg' => 'rpa任务正在执行，请稍等...'
                ];
            }else{
                //增加查询操作人
                //期货
                if(isset($cfa) && $cfa->state != -1){
                    $data1 = $this->getOperatorArray($cfa->operator,$cfa->count);
                    RpaShixincfa::where("id",$cfa->id)->update($data1);
                }
                //证券
                if(isset($sf) && $sf->state != -1){
                    $data2 = $this->getOperatorArray($sf->operator,$sf->count);
                    RpaShixinsf::where("id",$sf->id)->update($data2);
                }
                //金融
                if(isset($jr) && $jr->state != -1){
                    $data3 = $this->getOperatorArray($jr->operator,$jr->count);
                    RpaShixinsf::where("id",$jr->id)->update($data3);
                }

                $re = [
                    'status' => 200,
                    'qh' => $cfa->state,
                    'zq' => $sf->state,
                    'hs' => $jr->state
                ];
            }
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 失信查询
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function credit2(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'idCard' => 'required',
            'name' => 'required',
            'isSecondFinance' => 'required|in:0,1',
            'type' => 'required|in:1,2'
        ]);
        $today = date("Y-m-d H:i:s",time());
        $yesterday = date("Y-m-d",strtotime("-1 day"))." 15:00:00";
        //期货
        $cfa = RpaShixincfa::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //证券
        $sf = RpaShixinsf::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        //金融
        $jr = RpaShixinhss::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        if($request->isSecondFinance){
            //信用中国
            $xyzg = RpaShixinxyzg::where([["updatetime",'>=',$yesterday],["updatetime",'<=',$today],["idnum",$request->idCard],["name",$request->name]])->orderBy('id','desc')->first();
        }
        //$type 1代表发布任务 2代表查询结果
        if($request->type == 1){
            $param = [
                'name' => $request->name,
                'idCard' => $request->idCard,
                'operator' => Auth::user()->name
            ];
            //期货
            if(!isset($cfa) || $cfa->state == -1){
                $data1 = [
                    'name' => 'SupervisionCFA_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res1 = rpa_immedtasks::create($data1);
            }
            //证券
            if(!isset($sf) || $sf->state == -1){
                $data2 = [
                    'name' => 'SupervisionSF_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res2 = rpa_immedtasks::create($data2);
            }
            //金融
            if(!isset($jr) || $jr->state == -1){
                $data3 = [
                    'name' => 'SupervisionHS_im',
                    'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                ];
                $res3 = rpa_immedtasks::create($data3);
            }
            if($request->isSecondFinance){
                //信用中国
                if(!isset($xyzg) || $xyzg->state == -1){
                    $data4 = [
                        'name' => 'SupervisionXYZG_im',
                        'jsondata' => json_encode($param,JSON_UNESCAPED_UNICODE)
                    ];
                    $res4 = rpa_immedtasks::create($data4);
                }
            }


            $re = [
                'status' => 200,
                'msg' => 'rpa任务发布成功！'
            ];
        }else{
            if(!isset($cfa) || !isset($sf) || !isset($jr)){
                $re = [
                    'status' => 500,
                    'msg' => "未找到数据！"
                ];
            }elseif($cfa->state == null || $sf->state == null || $jr->state == null){
                $re = [
                    'status' => 500,
                    'msg' => 'rpa任务正在执行，请稍等...'
                ];
            }else{

                //增加查询操作人
                //期货
                if(isset($cfa) && $cfa->state != -1){
                    $data1 = $this->getOperatorArray($cfa->operator,$cfa->count);
                    RpaShixincfa::where("id",$cfa->id)->update($data1);
                }
                //证券
                if(isset($sf) && $sf->state != -1){
                    $data2 = $this->getOperatorArray($sf->operator,$sf->count);
                    RpaShixinsf::where("id",$sf->id)->update($data2);
                }
                //金融
                if(isset($jr) && $jr->state != -1){
                    $data3 = $this->getOperatorArray($jr->operator,$jr->count);
                    RpaShixinhss::where("id",$jr->id)->update($data3);
                }

                $re = [
                    'status' => 200,
                    'qh' => $cfa->state,
                    'zq' => $sf->state,
                    'hs' => $jr->state,
                    'xyzg'  => '2', //信用中国默认为2，无需查询
                ];

                //针对二次金融需要查询信用中国
                if($request->isSecondFinance){
                    if(!isset($xyzg)){
                        $re = [
                            'status' => 500,
                            'msg' => "未找到数据！"
                        ];
                    }elseif($xyzg->state == null){
                        $re = [
                            'status' => 500,
                            'msg' => 'rpa任务正在执行，请稍等...'
                        ];
                    }else{
                        if(isset($xyzg) && $xyzg->state != -1){
                            $data4 = $this->getOperatorArray($xyzg->operator,$xyzg->count);
                            RpaShixinxyzg::where("id",$xyzg->id)->update($data4);
                        }
                        $re['xyzg'] = $xyzg->state;
                    }
                }
            }
        }

        return response()->json($re);
    }

    /**
     * 写入预开户客户记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPreOpenAccount(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'zjbh' => 'required',
            'tel' => 'required',
        ]);

        $res = RpaPreOpenAccount::where([['zjbh','=',$request->zjbh],['name','=',$request->name]])->first();
        if(!$res){
            $add = [
                'name' => $request->name,
                'zjbh' => $request->zjbh,
                'tel' => $request->tel,
                'manager_number' => isset($request->manager_number) ? $request->manager_number : "",
                'manager_name' => isset($request->manager_name) ? $request->manager_name : "",
                'mediator_number' => isset($request->mediator_number) ? $request->mediator_number : "",
                'mediator_name' => isset($request->mediator_name) ? $request->mediator_name : "",
            ];
            $re = RpaPreOpenAccount::create($add);
            if($re){
                $return = [
                    'status' => 200,
                    'msg' => '录入成功！'
                ];
            }else{
                $return = [
                    'status' => 500,
                    'msg' => '录入失败！'
                ];
            }
        }else{
            $return = [
                'status' => 200,
                'msg' => '数据已存在，无需录入！'
            ];
        }

        return response()->json($return);
    }

    /**
     * 读取预开户客户记录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreOpenAccount(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'zjbh' => 'required',
        ]);

        $res = RpaPreOpenAccount::where([['zjbh','=',$request->zjbh],['name','=',$request->name]])->first();
        if($res){
            $return =[
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => '未查询到数据'
            ];
        }

        return response()->json($return);
    }

    /**
     * 复核同步客户数据到crm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync_data(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'fundsNum' => 'required|numeric'
        ]);

        $data = [
            'yybName' => isset($request->yyb) ? $request->yyb : "",
            'jjrNum' => isset($request->jjr) ? $request->jjr : "",
            'name' => isset($request->name) ? $request->name : "",
            'idCard' =>isset($request->idCard) ? $request->idCard : "",
            'customerNum' =>isset($request->customerNum) ? $request->customerNum : "",
            'fundsNum' =>$request->fundsNum,
            'sfz_date_begin' => isset($request->sfz_date_begin) ? $request->sfz_date_begin : "",
            'sfz_date_end' => isset($request->sfz_date_end) ? $request->sfz_date_end : "",
            'message' =>isset($request->message) ? $request->message : "",
            'creater' => isset($request->creater) ? $request->creater :Auth::user()->name,
            'add_time' => date('Y-m-d H:i:s'),
            'special' => trim($request->special,','),
            'is_visit' => 0,
            'is_script' => isset($request->is_script) ? 1 : 0
        ];
        //判断是否强制提交，如果是强制提交。验证错误时返回状态为true
        $comellent_submit = isset($request->comellent_submit) ? $request->comellent_submit : "";
        if($comellent_submit){
            $status = 200;
        }else{
            $status = 500;
        }
        //判断是否已提交
        //1.判断资金账号
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => [
                    ['ZJZH','=',$data['fundsNum']]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            //2.判断身份证号
            if($result[0]['ZJBH'] != $data['idCard']){
                $re = [
                    'status' => 500,
                    'msg' => "该资金账号已被占用！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
                return response()->json($re);
            }
        }
        // 判断特殊开户是否存在
        $res = rpa_customer_manager::where('fundsNum',$data['fundsNum'])->get();
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
                    $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                    return response()->json($re);
                }
            }
        }
        //非特殊客户，crm待处理信息表是否有相同资金账号
        if(!$data['special']){
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXXCL',
                    'by' => [
                        ['ZJZH','=',$data['fundsNum']],
                        ['CLZT','!=',4]
                    ]
                ]
            ];
            $result = $this->getCrmData($post_data);
            if($result){
                $re = [
                    'status' => 500,
                    'msg' => "CRM系统已存在该客户，请检查后再试！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                return response()->json($re);
            }
        }

        foreach($data as &$k){
            $k = addslashes($k);
        }

        //客户归属关系
        if('' != $data['customerNum'] && null != $data['customerNum']){

            //去crm获取
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXX',
                    'by' => [
                        ['BH','=',$data['customerNum']]
                    ],
                    'columns' => ['YYB','XM']
                ]
            ];
            $result = $this->getCrmData($post_data);
            if(isset($result[0])){
                //客户经理
                $manager = $result[0];
                $data['customerManagerName'] = $manager['XM'];
                //部门
                $sql = "select NAME from LBORGANIZATION where ID=".$manager['YYB']." order by ID desc";
                $post_data = [
                    'type' => 'common',
                    'action' => 'getEveryBy',
                    'param' => [
                        'table' => 'LBORGANIZATION',
                        'by' => $sql
                    ]
                ];
                $yyb = $this->getCrmData($post_data);
                if(isset($yyb[0])){
                    $yyb = $yyb[0];
                    $data['yybNum'] = $manager['YYB'];
                    $data['yybName'] = $data['yybName'] ? $data['yybName'] : $yyb['NAME'];
                }
                //居间人
                if($data['jjrNum']) {
                    $jjr = FuncMediatorInfo::where("number", $data['jjrNum'])->first();
                    $data['jjrName'] = $jjr->name;
                }
            }
        }else{
            //居间人信息
            if($data['jjrNum']){
                $jjr = FuncMediatorInfo::where("number", $data['jjrNum'])->first();
                if(isset($jjr)) {
                    $data['jjrName'] = $jjr->name;

                    $post_data = [
                        'type' => 'jjr',
                        'action' => 'get_mediator_relation',
                        'param' => [
                            'phone' => $jjr->phone,
                        ]
                    ];
                    $res = $this->getCrmData($post_data);
                    if($res){
                        $data['yybNum'] = $res['yyb_number'];
                        $data['yybName'] = $data['yybName'] ? $data['yybName'] : $res['yyb_name'];
                    }
                }
            }
        }
        //写入rpa
        $data['KHRQ'] = date('Y-m-d');
        $result2 = rpa_customer_manager::create($data);

        if($result2){

            //发布客户分组任务
            $jsondata = [
                'zjzh' => $data['fundsNum'],
                'dept' => $data['yybName']
            ];
            $timetask = [
                'time' => date("Y-m-d H:i:s",time()+180),
                'name' => 'CustomerGroupings',
                'jsondata' => json_encode($jsondata,JSON_UNESCAPED_UNICODE),
                'description' => '客户分组'

            ];
            rpa_timetasks::create($timetask);



            /****event sync 同步线上开户客户回访列表 -- （2020-01-13 hsu lay）****/
            //已关闭运行 20200326 hsulay

            //修改 增加只处理居间客户 (2020-03-24  hsu lay)
            // if($data['jjrNum']){
            //     $event_customer = $data;
            //     $event_customer['id'] = $result2;
            //     event(new SyncOfflineCustomer($event_customer, 1));
            // }

            /*********************************end*******************************/


            //开户插件同步居间关系到crm系统
            //增加二次股指、激活、更新判断，以上客户不同步到crm
            if(!$data['special']){

                //发布银期关联任务
                $jsondata = [
                    'zjzh' => $data['fundsNum'],
                    'uid' => "{$result2->id}",
                ];
                rpa_timetasks::create([
                    'time' => date("Y-m-d H:i:s",time()+180),
                    'name' => 'ReleaseRelationTask',
                    'jsondata' => json_encode($jsondata, JSON_UNESCAPED_UNICODE),
                    'description' => '自动银期'
                ]);

                $post_data = [
                    'type' => 'customer',
                    'action' => 'relationCustomer',
                    'param' => [
                        'info' => $data
                    ]
                ];
                $result = $this->getCrmData($post_data);
                if(!$result){
                    $re = [
                        'status' => 500,
                        'msg' => "CRM系统推送数据失败，请联系金融科技部！"
                    ];

                    //api日志
                    $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

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
                'msg' => "RPA系统信息录入失败，请联系金融科技部！"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 复核同步客户数据到crm
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync_data2(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'fundsNum' => 'required|numeric',
            'isSecondFinance' => 'required|in:0,1',
        ]);

        $data = [
            'yybName' => isset($request->yyb) ? $request->yyb : "",
            'jjrNum' => isset($request->jjr) ? $request->jjr : "",
            'name' => isset($request->name) ? $request->name : "",
            'idCard' =>isset($request->idCard) ? $request->idCard : "",
            'customerNum' =>isset($request->customerNum) ? $request->customerNum : "",
            'fundsNum' =>$request->fundsNum,
            'sfz_date_begin' => isset($request->sfz_date_begin) ? $request->sfz_date_begin : "",
            'sfz_date_end' => isset($request->sfz_date_end) ? $request->sfz_date_end : "",
            'message' =>isset($request->message) ? $request->message : "",
            'creater' => isset($request->creater) ? $request->creater :Auth::user()->name,
            'add_time' => date('Y-m-d H:i:s'),
            'special' => trim($request->special,','),
            'is_visit' => 0,
            'is_script' => isset($request->is_script) ? 1 : 0
        ];
        //判断是否强制提交，如果是强制提交。验证错误时返回状态为true
        $comellent_submit = isset($request->comellent_submit) ? $request->comellent_submit : "";
        if($comellent_submit){
            $status = 200;
        }else{
            $status = 500;
        }
        //判断是否已提交
        //1.判断资金账号
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'KHXX',
                'by' => [
                    ['ZJZH','=',$data['fundsNum']]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            //2.判断身份证号
            if($result[0]['ZJBH'] != $data['idCard']){
                $re = [
                    'status' => 500,
                    'msg' => "该资金账号已被占用！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());
                return response()->json($re);
            }
        }
        // 判断特殊开户是否存在
        $res = rpa_customer_manager::where('fundsNum',$data['fundsNum'])->get();
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
                    $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                    return response()->json($re);
                }
            }
        }
        //非特殊客户，crm待处理信息表是否有相同资金账号
        if(!$data['special']){
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXXCL',
                    'by' => [
                        ['ZJZH','=',$data['fundsNum']],
                        ['CLZT','!=',4]
                    ]
                ]
            ];
            $result = $this->getCrmData($post_data);
            if($result){
                $re = [
                    'status' => 500,
                    'msg' => "CRM系统已存在该客户，请检查后再试！"
                ];

                //api日志
                $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

                return response()->json($re);
            }
        }

        foreach($data as &$k){
            $k = addslashes($k);
        }

        //客户归属关系
        if('' != $data['customerNum'] && null != $data['customerNum']){

            //去crm获取
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'YGXX',
                    'by' => [
                        ['BH','=',$data['customerNum']]
                    ],
                    'columns' => ['YYB','XM']
                ]
            ];
            $result = $this->getCrmData($post_data);
            if(isset($result[0])){
                //客户经理
                $manager = $result[0];
                $data['customerManagerName'] = $manager['XM'];
                //部门
                $sql = "select NAME from LBORGANIZATION where ID=".$manager['YYB']." order by ID desc";
                $post_data = [
                    'type' => 'common',
                    'action' => 'getEveryBy',
                    'param' => [
                        'table' => 'LBORGANIZATION',
                        'by' => $sql
                    ]
                ];
                $yyb = $this->getCrmData($post_data);
                if(isset($yyb[0])){
                    $yyb = $yyb[0];
                    $data['yybNum'] = $manager['YYB'];
                    $data['yybName'] = $data['yybName'] ? $data['yybName'] : $yyb['NAME'];
                }
                //居间人
                if($data['jjrNum']) {
                    $jjr = FuncMediatorInfo::where("number", $data['jjrNum'])->first();
                    $data['jjrName'] = $jjr->name;
                }
            }
        }else{
            //居间人信息
            if($data['jjrNum']){
                $jjr = FuncMediatorInfo::where("number", $data['jjrNum'])->first();
                if(isset($jjr)) {
                    $data['jjrName'] = $jjr->name;

                    $post_data = [
                        'type' => 'jjr',
                        'action' => 'get_mediator_relation',
                        'param' => [
                            'phone' => $jjr->phone,
                        ]
                    ];
                    $res = $this->getCrmData($post_data);
                    if($res){
                        $data['yybNum'] = $res['yyb_number'];
                        $data['yybName'] = $data['yybName'] ? $data['yybName'] : $res['yyb_name'];
                    }
                }
            }
        }
        //写入rpa
        $data['KHRQ'] = date('Y-m-d');
        $result2 = rpa_customer_manager::create($data);
        //二次金融单独记录
        if($request->isSecondFinance){
            $add = [
                'name' => $data['name'],
                'idCard' => $data['idCard'],
                'fundsNum' => $data['fundsNum'],
                'yybNum' => $data['yybNum'],
                'yybName' => $data['yybName'],
                'customerNum' => isset($data['customerNum']) ? $data['customerNum'] : "",
                'customerManagerName' => isset($data['customerManagerName']) ? $data['customerManagerName'] : "",
                'jjrNum' => isset($data['jjrNum']) ? $data['jjrNum'] : "",
                'jjrName' => isset($data['jjrName']) ? $data['jjrName'] : "",
                'creater' => $data['creater'],
                'open_date' => $data['KHRQ']
            ];
            RpaCustomerSecondFinance::create($add);
        }

        if($result2){

            //发布客户分组任务
            $jsondata = [
                'zjzh' => $data['fundsNum'],
                'dept' => $data['yybName']
            ];
            $timetask = [
                'time' => date("Y-m-d H:i:s",time()+180),
                'name' => 'CustomerGroupings',
                'jsondata' => json_encode($jsondata,JSON_UNESCAPED_UNICODE),
                'description' => '客户分组'

            ];
            rpa_timetasks::create($timetask);



            /****event sync 同步线上开户客户回访列表 -- （2020-01-13 hsu lay）****/
            //已关闭运行 20200326 hsulay

            //修改 增加只处理居间客户 (2020-03-24  hsu lay)
            // if($data['jjrNum']){
            //     $event_customer = $data;
            //     $event_customer['id'] = $result2;
            //     event(new SyncOfflineCustomer($event_customer, 1));
            // }

            /*********************************end*******************************/


            //开户插件同步居间关系到crm系统
            //增加二次股指、激活、更新判断，以上客户不同步到crm
            if(!$data['special']){

                $add = [
                    'mid' => $result2->id
                ];

                $tmp = RpaBankRelationTmp::create($add);
                //发布银期关联任务
                $jsondata = [
                    'zjzh' => $data['fundsNum'],
                    'uid' => "{$tmp->id}",
                ];
                rpa_timetasks::create([
                    'time' => date("Y-m-d H:i:s",time()+180),
                    'name' => 'ReleaseRelationTask',
                    'jsondata' => json_encode($jsondata, JSON_UNESCAPED_UNICODE),
                    'description' => '自动银期'
                ]);

                $post_data = [
                    'type' => 'customer',
                    'action' => 'relationCustomer',
                    'param' => [
                        'info' => $data
                    ]
                ];
                $result = $this->getCrmData($post_data);
                if(!$result){
                    $re = [
                        'status' => 500,
                        'msg' => "CRM系统推送数据失败，请联系金融科技部！"
                    ];

                    //api日志
                    $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

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
                'msg' => "RPA系统信息录入失败，请联系金融科技部！"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 保存客户信息及影像资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_customer_info(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'zjzh' => 'required',
            'head_zm' => 'required',
            'sfz_zm' => 'required',
            'sfz_fm' => 'required',
            'sign' => 'required',
        ]);

        $root_path = "d:/uploadFile/customerImg/";
        $date_path = "/".date("Y")."/".date("Ym")."/".date("Ymd")."/";

        $files = [
            'head_zm','sfz_zm','sfz_fm','sign'
        ];

        $add = [
            'fundAccount' => $request->zjzh,
            'operator' => Auth::user()->name,
            'state' => 2,
        ];
        foreach($files as $v){
            $file = $request->file($v);
            $filename = time() . '_' . uniqid() .".".$file->clientExtension();
            $path = $root_path.$v.$date_path;
            $file->move($path,$filename);
            $add[$v] = $v.$date_path.$filename;
        }

        $res = RpaCustomerInfo::create($add);
        if($res){
            //身份证识别
            $result = RpaCustomerInfo::where("fundAccount",$request->zjzh)->first();
            if($result->sfz_zm){

                $server = $this->get_config();
                if($server == 'H1_inner'){
                    $s = '主服务器1';
                }elseif($server == 'H2_inner'){
                    $s = '主服务器2';
                }

                $data = [
                    'name' => "IDRecognition",
                    'jsondata' => json_encode(['ids' => (string)$result->id],JSON_UNESCAPED_UNICODE),
                    'server' => $s
                ];
                //插入即时任务
                rpa_immedtasks::create($data);
            }

            $return = [
                'status' => 200,
                'msg' => '保存成功！'
            ];
        }else{
            $return = [
                'status' => 500,
                'msg' => '保存失败！'
            ];
        }

        return response()->json($return);
    }

    /**
     * 拼接操作人json
     * @param $operator string 历史操作人
     * @param $count  integer  查询次数
     * @return array
     */
    private function getOperatorArray($operator,$count)
    {
        $opera = json_decode($operator,true);
        if(is_array($operator)){
            $opera[] = [
                'name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s',time())
            ];
        }else{
            $opera = [
                'name' => Auth::user()->name,
                'date' => date('Y-m-d H:i:s',time())
            ];
        }

        $data = [
            'operator' => json_encode($opera,JSON_UNESCAPED_UNICODE),
            'count' => $count + 1
        ];
        return $data;
    }
}
