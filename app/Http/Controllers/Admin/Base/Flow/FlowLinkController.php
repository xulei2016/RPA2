<?php

namespace App\Http\Controllers\Admin\Base\Flow;

use DB;
use Illuminate\Http\Request;
use App\Models\Admin\Base\Flow\SysFlow;
use App\Models\Admin\Base\Flow\SysFlowLink;
use App\Models\Admin\Base\Flow\SysFlowNode;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * SysFlowLinkController class
 *
 * @Description 流程流转中心
 */
class FlowLinkController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        try{
            DB::beginTransaction();
            // node_info:{"9023":{"top":175,"left":492,"node_to":[]},"9024":{"top":427,"left":453,"node_to":[]},"9026":{"top":328,"left":865,"node_to":[]},"9028":{"top":244,"left":201,"node_to":[]},"9033":{"top":427,"left":572,"node_to":[]},"9036":{"top":162,"left":1105,"node_to":[]},"9037":{"top":109,"left":235,"node_to":[]},"9038":{"top":91,"left":460,"node_to":[]},"9074":{"top":201,"left":117,"node_to":[]},"9075":{"top":264,"left":435,"node_to":[]},"9076":{"top":112,"left":764,"node_to":[]}}
        
            //保存流程设计
            // $flow_id=$request->input('flow_id',0);
            $flow_id = $id;

            //TODO 更新flow 表 jsplumb json数据. 更新流程轨迹 SysFlowLink表 type=Condition
            
            $node_info=json_decode($request->input('node_info',[]),true);

            //删除节点后保存 过滤掉删除节点
            // if($del_node_id=session('del_node_id')){
            //     foreach($node_info as $k=>$v){
            //         if($k==$del_node_id){
            //             unset($node_info[$k]);
            //         }
            //     }
            //     session()->forget('del_node_id');
            // }

            if(!empty($node_info)){
                $flow=SysFlow::findOrFail($id);

                //更新flow 表 jsplumb json数据
                $jsplumb=json_decode($flow->jsplumb,true);
                $jsplumb['total']=count($node_info);

                foreach($node_info as $k=>$v){
                    //更新flow 表 jsplumb json数据
                    foreach($jsplumb['list'] as $i=>$j){
                        if($k==$j['id']){
                            $node=SysFlowNode::where(['id'=>$k])->first();
                            // dd($node);
                            $node->update([
                                'style'=>'width:'.$node->style_width.'px;height:'.$node->style_width.'px;line-height:30px;color:'.$node->style_color.';left:'.$v['left'].'px;top:'.$v['top'].'px;',
                                'position_left'=>$v['left'].'px',
                                'position_top'=>$v['top'].'px'
                            ]);

                            $jsplumb['list'][$i]['node_to']=implode(',',$v['node_to']);
                            $jsplumb['list'][$i]['style']='width:'.$node->style_width.'px;height:'.$node->style_height.'px;line-height:30px;color:'.$node->style_color.';left:'.$v['left'].'px;top:'.$v['top'].'px;';
                            
                        }
                    }

                    //更新流程轨迹 SysFlowLink表 type=Condition
                    $old_node_ids=SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->pluck('next_node_id')->toArray();

                    if(!empty($v['node_to'])){

                        if($v['node_to']!=$old_node_ids){
                            //有变动
                            //新增连线
                            $adds=array_diff($v['node_to'],$old_node_ids);
                            foreach($adds as $a){
                                SysFlowLink::create([
                                    'flow_id'=>$id,
                                    'type'=>'Condition',
                                    'node_id'=>$k,
                                    'next_node_id'=>$a,
                                    'sort'=>100
                                ]);
                            }

                            //删除的连线
                            $dels=array_diff($old_node_ids,$v['node_to']);
                            SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->whereIn('next_node_id',$dels)->delete();
                        }
                    }else{
                        if(count($old_node_ids)>1){
                            //只保留一个
                            $old_id=array_pop($old_node_ids);

                            SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->whereIn('next_node_id',$old_node_ids)->delete();

                            SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$old_id])->update([
                                'next_node_id'=>-1,
                            ]);
                        }else{
                            if(SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->first()){
                                SysFlowLink::where(['flow_id'=>$id,'type'=>'Condition','node_id'=>$k])->update([
                                    'next_node_id'=>-1,
                                ]);
                            }else{
                                SysFlowLink::create([
                                    'flow_id'=>$id,
                                    'type'=>'Condition',
                                    'node_id'=>$k,
                                    'next_node_id'=>-1,
                                    'sort'=>100
                                ]);
                            }
                        }
                    }
                }
                $flow->jsplumb=json_encode($jsplumb);
                $flow->is_publish=0;
                $flow->save();
            }

            DB::commit();
            return response()->json(['status_code'=>0,'message'=>'更新成功']);
        }catch(\Eexception $e){
            DB::rollabck();
            dd($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
