<?php

namespace App\Http\Controllers\Admin\Func;

use App\Models\Admin\Func\rpa_customer_videos;
use Illuminate\Http\Request;
use App\Models\Admin\Func\rpa_customer_videos as videos;
use App\Http\Controllers\Base\BaseAdminController;
use Excel;

/**
 * VideoCollectController class
 *
 * @Description 线下客户视频收集
 * @author Hsu Lay
 * @since 2019-05-15
 */
class VideoCollectController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "视频 列表页");
        return view('Admin.Func.CustomerVideos.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "视频 审核页");
        $customer = rpa_customer_videos::find($id);
        return view('Admin.Func.CustomerVideos.check', ['customer'=>$customer]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "视频 审核");
        $id = $request->id;
        //更新数据
        $data = $this->get_params($request, [['status',2],['reason', '']]);
        $customer = rpa_customer_videos::find($id);
        if($data['status'] == 2){
            //根据id删除视频文件
            $videos = json_decode($customer->jsondata,true);
            foreach($videos as $k=>$v){
                unlink(".".$v['path']);
                $videos[$k]['state'] = 0;
            }
            $jsondata = json_encode($videos);
            $data['jsondata'] = $jsondata;
        }else{
            //修改文件名
            $fileList = json_decode($customer->jsondata,true);
            $i = 0;
            foreach($fileList as $k=>$v){
                if($v['state'] == 1){
                    $newRemark = $request->input("newRemark_".$i);
                    $newPath = str_replace($v['remark'],$newRemark,$v['path']);
                    //修改文件名
                    rename(iconv('utf-8','gb2312',$_SERVER['DOCUMENT_ROOT'].$v['path']),iconv('utf-8','gb2312',$_SERVER['DOCUMENT_ROOT'].$newPath));
                    //修改数据库
                    $fileList[$k]['filename'] = str_replace($v['remark'],$newRemark,$v['filename']);
                    $fileList[$k]['path'] = $newPath;
                    $fileList[$k]['remark'] = $newRemark;
                    $i++;
                }
            }
            $data['jsondata'] = json_encode($fileList);
        }
        
        rpa_customer_videos::where("id",$id)->update($data);
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //根据id删除视频文件
        $customer = rpa_customer_videos::where('id',$id)->first();
        $videos = json_decode($customer->jsondata,true);
        foreach($videos as $k=>$v){
            unlink(".".$v['path']);
        }
        
        $result = rpa_customer_videos::destroy($id);
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 视频记录");
        return $this->ajax_return(200, '操作成功！');
    }

    /**
     * pagenation
     * 
     * @return \Illuminate\Http\Response
     */
    public function pagenation(Request $request)
    {
        $rows = $request->rows;
        $param = $this->get_params($request, ['status', 'from_created_at', 'to_created_at']);
        $conditions = $this->getPagingList($param, ['status'=>'=', 'from_created_at'=>'>=', 'to_created_at'=>'<=']);
        $order = $request->sort ?? 'status';
        $sort = $request->sortOrder;
        $result = videos::where($conditions)
                ->orderBy($order, $sort)
                ->paginate($rows);
        return $result;
    }

    /**
     * export
     * 
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $param = $this->get_params($request, ['status', 'from_created_at', 'to_created_at']);
        $conditions = $this->getPagingList($param, ['status'=>'=', 'from_created_at'=>'>=', 'to_created_at'=>'<=']);

        if(isset($param['id'])){
            $data = videos::where($conditions)->whereIn('id', explode(',',$param['id']))->get()->toArray();
        }else{
            $data = videos::where($conditions)->get()->toArray();
        }
        
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "导出视频记录");
        Excel::create('线下视频记录表',function($excel) use ($cellData){
            $excel->sheet('信息库', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

}
