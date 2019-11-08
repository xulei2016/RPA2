<?php

namespace App\Http\Controllers\api\rpa;

use App\Http\Controllers\api\BaseApiController;
use App\Jobs\File;
use App\Models\Admin\Func\rpa_customer_videos;
use Illuminate\Http\Request;

class VideoCollectApiController extends BaseApiController
{
    /**
     * 登录验证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        //ip检测
//        $res = $this->check_ip(__FUNCTION__,$request->getClientIp());
//        if($res !== true){
//            return response()->json($res);
//        }

        //表单验证
        $validatedData = $request->validate([
            'MNum' => 'required|numeric',
            'MName' => 'required',
        ]);

        //crm登录验证
        $post_data = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => 'YGXX',
                'by' => [
                    ['XM','=',$request->MName],
                    ['BH','=',$request->MNum]
                ]
            ]
        ];
        $result = $this->getCrmData($post_data);
        
        if($result){
            //根据部门编号获取部门
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'LBORGANIZATION',
                    'by' => [
                        ['ID','=',$result[0]['YYB']],
                    ]
                ]
            ];
            $yyb = $this->getCrmData($post_data);
            $re = [
                'status' => 200,
                'data' => $yyb[0]['NAME'],
            ];
        }else{
            $re = [
                'status' => 500,
                'data' => '登录失败'
            ];
        }
        return response()->json($re);
    }

    /**
     * 上传的客户信息
     * @param Request $request
     */
    public function customer(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'yyb' => 'required',
            'MNum' => 'required|numeric',
            'MName' => 'required',
            'FundAccount' => 'required|numeric',
            'CName' => 'required',
            'CidCard' => 'required',
        ]);

        $customer = rpa_customer_videos::where("customer_zjzh",$request->FundAccount)->first();
        if(!$customer){
        //  新客户
        //  crm验证客户信息
            $post_data = [
                'type' => 'common',
                'action' => 'getEveryBy',
                'param' => [
                    'table' => 'KHXX',
                    'by' => [
                        ['ZJZH','=',$request->FundAccount],
                        ['KHXM','=',$request->CName],
                        ['ZJBH','=',$request->CidCard]
                    ]
                ]
            ];
            $customer = $this->getCrmData($post_data);
            if($customer){
                $data = [
                    'yyb' => $request->yyb,
                    'jlr_name' => $request->MName,
                    'jlr_bh' => $request->MNum,
                    'customer_name' => $request->CName,
                    'customer_sfzh' => $request->CidCard,
                    'customer_zjzh' => $request->FundAccount,
                ];
    
                $res = rpa_customer_videos::create($data);
                if($res){
                    $re = [
                        'status' => 200,
                        'data' => $res->id
                    ];
                }else{
                    $re = [
                        'status' => 500,
                        'data' => '添加失败！'
                    ];
                }
            }else{
                $re = [
                    'status' => 500,
                    'data' => '客户信息填写错误！'
                ];
            }
            
        }else{
            //   老客户
            if($customer->status == 1){
                $re = [
                    'status' => 500,
                    'data' => '该客户已经归档，无法继续上传！'
                ];
            }else{
                $re = [
                    'status' => 200,
                    'data' => $customer->id
                ];
            }
        }
        return response()->json($re);

    }

    public function getRemark(Request $request){
        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric',
        ]);
        $customer = rpa_customer_videos::where("id",$request->id)->first();
        $jsondata = json_decode($customer->jsondata,true);
        $remarks = [];
        foreach($jsondata as $v){
            $remarks[] = $v['remark'];
        }
        return response()->json($remarks);
    }

    /**
     * 文件上传，支持断点续传
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {

        //表单验证
        $validatedData = $request->validate([
            'id' => 'required|numeric',
            'blobNum' => 'required|numeric',
            'totalBlobNum' => 'required|numeric',
            'remark' => 'required',
            'filename' => 'required',
            'filesize' => 'required|numeric',
        ]);

        $id = $request->id;//id
        $uploadPath = public_path()."/uploads/Customer_video/".$id."/"; //上传目录
        $uploadPath2 = "/uploads/Customer_video/".$id."/";
        //接收参数
        $blobNum = $request->blobNum;//第几个文件块
        $totalBlobNum = $request->totalBlobNum; //文件块总数
        $filename = $request->filename;//文件名
        $fileremark = $request->remark; //文件备注
        $filesize = $request->filesize;//文件大小
        //生成文件名
        $file = $request->file("video");
        //移动文件
        if(!is_dir($uploadPath)){
            mkdir($uploadPath,0777);
        }
        $tempfilename =  $filename.'_'.$blobNum;
        $ext = substr($filename, strrpos($filename, '.')+1);
        $file->move($uploadPath,$tempfilename);
        //判断是否是最后一块，如果是则进行文件合成并且删除文件块
        if($blobNum == $totalBlobNum-1){
            $param = [
                'id' => $id,
                'fileremark' => $fileremark,
                'ext' => $ext,
                'totalBlobNum' => $totalBlobNum,
                'filename' => $filename,
                'uploadPath' => $uploadPath,
                'uploadPath2' => $uploadPath2
            ];
            File::dispatch($param);
        }
        $re = [
            'status' => 200,
            'data' => '上传成功，第'.$blobNum."总共".$totalBlobNum
        ];
        return response()->json($re);
    }

    /**
     * 上传历史
     * @param Request $request
     * @return mixed
     */
    public function history(Request $request)
    {
        //表单验证
        $validatedData = $request->validate([
            'MNum' => 'required|numeric',
        ]);
        $customer = rpa_customer_videos::where("jlr_bh",$request->MNum)->get();
        foreach($customer as $k => $v){
            $customer[$k]->jsondata = json_decode($v->jsondata,true);
        }
        return response()->json($customer);
    }
}
