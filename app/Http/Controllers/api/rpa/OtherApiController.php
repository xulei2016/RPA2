<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class OtherApiController extends BaseApiController
{
    /**
     * 获取客户关系（投资江湖）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_customer_relation(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'account' => 'required|numeric',
            'name' => 'required'
        ]);

        //根据姓名加资金账号查询客户
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'TKHXX',
                'by' => [
                    ['KHXM','=',$request->name],
                    ['ZJZH','=',$request->account]
                ],
                'columns' => ['YYB','ID']
            ]
        ];
        $result = $this->getCrmData($post_data);
        if($result){
            $post_data = [
                'type' => 'jjr',
                'action' => 'get_customer_relation',
                'param' => [
                    'yyb_number' => $result[0]['YYB'],
                    'kid' => $result[0]['ID']
                ]
            ];
            $res = $this->getCrmData($post_data);
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => '该资金账号未找到'
            ];
        }

        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);
    }

    /**
     * 获取居间关系（投资江湖）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_mediator_relation(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'phone' => 'required|numeric'
        ]);
        
        $post_data = [
            'type' => 'jjr',
            'action' => 'get_mediator_relation',
            'param' => [
                'phone' => $request->phone
            ]
        ];
        $res = $this->getCrmData($post_data);
        if($res){
            $re = [
                'status' => 200,
                'msg' => $res
            ];
        }else{
            $re = [
                'status' => 500,
                'msg' => "居间不存在或者无效居间"
            ];
        }
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($re,true),$request->getClientIp());

        return response()->json($re);

    }

    /**
     * 居间人流程同步
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mediator_flow(Request $request)
    {
        //ip检测
        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
        if($res !== true){
            return response()->json($res);
        }

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric',
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
        $this->apiLog(__FUNCTION__,$request,json_encode($return,true),$request->getClientIp());

        return response()->json($return);
    }

    public function test(Request $request)
    {
        
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
                case "TXCTC_LC_JJRBLQR": //居间人比例确认
                    $re = $this->jjrBLQR($lc->instid);
                default:
                    $re = [
                        'status' => 500,
                        'msg' => "该流程不是居间新增,变更,续签,比例流程！"
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

        //成功
        $re = [
            'status' => 200,
            'msg' => "新增流程{$instid},数据同步成功"
        ];
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

        $re = [
            'status' => 200,
            'msg' => "修改流程{$instid},数据同步成功"
        ];
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
        
        $re = [
            'status' => 200,
            'msg' => "续签流程{$instid},数据同步成功"
        ];
                
        return $re;
    }

    /**
     * 居间比例确认流程
     * @param $instid
     * @return array
     */
    private function jjrBLQR($instid){
		//调用接口，保存文件
		$table = 'TXCTC_LC_JJRBLQR';
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
                'msg' => "续签流程{$instid}附件保存失败"
            ];
            return $re;
        }
		//发送短信 
		$content="您好！您在我公司申请的居间协议已办理成功！客户经理号为{$savepath['JYGH']}，居间编号为{$savepath['BH']}。如有疑问请及时与您的业务经理保持联系或拨打客服电话400-8820-628";
		//生成协议
		if($savepath['uid']){

			$post_data = [
				'uid' => $savepath['uid'],
				'path' => $savepath['path'],
				'sms_content' => $content
			];
            
            $guzzle = new Client();
            $response = $guzzle->post('http:/172.16.191.26/oa2_test/index.php?m=Xy&a=xyHB2',[
                'form_params' => $post_data,
            ]);
            $body = $response->getBody();
            $result = json_decode((String)$body,true);
		}
		$re = [
			'status' => 200,
			'msg' => "比例申请流程{$instid}数据同步成功"
		];
		return $re;
	}

    /**
     * 保存文件
     * @param $postdata
     * @return bool|mixed
     */
    public function save_file($postdata){
        $guzzle = new Client();
        $response = $guzzle->post('http://172.16.191.26/interface/yxxt/yxxt.php',[
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
}
