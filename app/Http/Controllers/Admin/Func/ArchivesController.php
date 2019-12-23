<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\Base\BaseAdminController;
use App\Models\Admin\Func\Archives\func_archives;
use App\Models\Admin\Func\Archives\func_archives_files;
use App\Models\Admin\Func\rpa_customer_videos;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ArchivesController extends BaseAdminController{

    public function index(Request $request)
    {
        return view('admin/func/Archives/index');
    }

    public function create(Request $request)
    {
        return view('admin/func/Archives/add');
    }

    public function edit(Request $request,$id)
    {
        $archives = func_archives::where('id',$id)->first();
        return view('admin/func/Archives/edit',['archives' => $archives]);
    }

    public function store(Request $request)
    {
        $data = $this->get_params($request, ['user_id','name','zjbh','type','btype']);
        $data['step'] = 1;
        $uid = isset($data['user_id']) ? $data['user_id'] : '';
        unset($data['user_id']);
        if($uid != ''){
            func_archives::where('id',$uid)->update($data);
            $id = $uid;
        }else{
            $r= func_archives::where([['zjbh',$data['zjbh']],['btype',$data['btype']]])->first();
            if(!$r){
                $res = func_archives::create($data);
                $id = $res->id;
            }else{
                $id = $r->id;
            }
        }

        $this->log(__CLASS__, __FUNCTION__, $request, "添加 线下档案收集");
        return $this->ajax_return(200, '操作成功！',['id'=>$id]);
    }

    public function pagenation(Request $request)
    {
        $selectInfo = $this->get_params($request, ['name','from_update_at','to_update_at']);

        $condition = $this->getPagingList($selectInfo, ['name'=>'=','from_update_at'=>'>=','to_update_at'=>'<=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        return func_archives::where($condition)->orderBy($order,$sort)->paginate($rows);
    }

    /**
     * 异步查询视频是否审核
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectVideo(Request $request)
    {
        $selectInfo = $this->get_params($request,["customer_name","customer_sfzh","business_type"]);
        $condition = $this->getPagingList($selectInfo, ['customer_name'=>'=','customer_sfzh'=>'=','business_type'=>'=']);
        $video = rpa_customer_videos::where($condition)->first();
        if($video){
            if($video->status == 1){
                $re = [
                    'status' => 200,
                    'msg' => '审核通过'
                ];
            }else{
                $re = [
                    'status' => 501,
                    'msg' => $video->id
                ];
            }
        }else{
            $re = [
                'status' => 500,
                'msg' => '视频未上传'
            ];
        }
        return response()->json($re);
    }

    /**
     * 失信查询
     * @param Request $request
     * @return mixed
     */
    public function credit(Request $request)
    {
        $data = [
            'name' => $request->name,
            'idCard' => $request->idCard,
            'type' => $request->type
        ];
        $guzzle = new Client(['verify'=>false]);
        $host = "https://rpa.slave.haqh.com:8088";
        //$host = $request->getHttpHost();
        $token = $this->access_token($host);
        $response = $guzzle->post($host.'/api/v2/credit',[
            'headers'=>[
                'Accept' => 'application/json',
                'Authorization' => $token
            ],
            'form_params' => $data
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        return $result;
    }

    /**
     * 上传附件
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadEnclosure(Request $request)
    {
        $id = $request->id;
        $file = $request->file('file');
        $name = $file->getClientOriginalName();

        //文件名称增加日期时间
        $name = date("Y_m_d_His") . "_" . $name;

        $dir = 'D:/uploadFile/Archives/'.$id."/enclosure/";
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $file->move($dir, $name);

        $add = [
            'archives_id' => $id,
            'type' => '附件',
            'path' => $dir.$name
        ];
        func_archives_files::create($add);

        $re = [
            'status' => 200,
            'msg' => '上传成功'
        ];

        return response()->json($re);
    }

    /**
     * 上传音频
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAudio(Request $request)
    {
        $id = $request->id;
        $file = $request->file('file');
        $name = $file->getClientOriginalName();

        //文件名称增加日期时间
        $name = date("Y_m_d_His") . "_" . $name;

        $dir = 'D:/uploadFile/Archives/'.$id."/audio/";
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $file->move($dir, $name);

        $add = [
            'archives_id' => $id,
            'type' => '音频',
            'path' => $dir.$name
        ];
        func_archives_files::create($add);

        $re = [
            'status' => 200,
            'msg' => '上传成功'
        ];

        return response()->json($re);
    }

    /**
     * 查询适当性等级
     * @param Request $request
     * @return mixed
     */
    public function selectSdxLevel(Request $request)
    {
        if($request->customer_type == '个人'){
            $idkind = 0;
        }else{
            $idkind = 2;
        }
        $data = [
            'idKind' => $idkind,
            'idNo' => $request->customer_zjbh
        ];
        $guzzle = new Client(['verify'=>false]);
        $host = $request->getHttpHost();
        $token = $this->access_token($host);
        $response = $guzzle->post($host.'/api/v1/getSdx',[
            'headers'=>[
                'Accept' => 'application/json',
                'Authorization' => $token
            ],
            'form_params' => $data
        ]);
        $body = $response->getBody();
        $result = json_decode((String)$body,true);
        if($result['status'] == 200){
            $res = [
                'status' => 200,
                'msg' => [
                    'corpRiskLevel' => "C".$result['msg'][0]['corpRiskLevel'],
                    'corpBeginDate' => $result['msg'][0]['corpBeginDate'],
                    'corpEndDate' => $result['msg'][0]['corpEndDate'],
                ]
            ];
        }else{
            $res = [
                'status' => 500,
                'msg' => '未进行适当性测评！'
            ];
        }

        return $res;
    }

    /**
     * 修改步骤
     * @param Request $request
     */
    public function changeStep(Request $request)
    {
        $id = $request->id;
        $update = [
            'step' => $request->step
        ];
        if(isset($request->list)){
            $update['credit_list'] = $request->list;
        }

        func_archives::where('id',$id)->update($update);
    }
}
