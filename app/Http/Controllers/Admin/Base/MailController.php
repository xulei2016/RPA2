<?php

namespace App\Http\Controllers\admin\base;

use App\Mail\MdEmail;
use App\Models\Admin\Base\SysMailType;
use App\Models\Admin\Base\SysMailMode;
use App\Models\Admin\Base\SysUserMail;
use App\Models\Admin\Base\SysMailOutbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * MailController
 * @author lay
 */
class MailController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $global = self::mailStatistics();
        return view('admin.base.mail.index', ['global' => $global]);
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
        //
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
    
    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['title','type']);
        $data['uid'] = [auth()->guard()->user()->id];
        $conditions = $this->getPagingList($data, ['title'=>'like', 'uid'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'mid';
        $sort = $request->sortOrder;
        $result = SysUserMail::where($conditions)
                ->join('sys_mail_outboxs', 'sys_user_mails.uid', '=', 'sys_mail_outboxs.id')
                ->leftJoin('sys_mail_types', 'sys_mail_outboxs.tid', '=', 'sys_mail_types.id')
                ->leftJoin('sys_mail_modes', 'sys_mail_outboxs.mid', '=', 'sys_mail_modes.id')
                ->select(['sys_mail_outboxs.*', 'sys_user_mails.is_read', 'sys_mail_types.desc as type', 'sys_mail_modes.desc as mode'])
                ->orderBy('sys_user_mails.is_read', 'desc')
                ->orderBy($order, $sort)
                ->paginate($rows);
        return $result;
    }

    /**
     * export
     */
    public function export(Request $request){
        $param = $this->get_params($request, ['name', 'type', 'id']);
        $conditions = $this->getPagingList($param, ['name'=>'like', 'type'=>'=']);

        if(isset($param['id'])){
            $data = SysUserMail::where($conditions)->whereIn('id', explode(',',$param['id']))->get()->toArray();
        }else{
            $data = SysUserMail::where($conditions)->get()->toArray();
        }
        
        $cellData = [];
        $cellData[] = array_keys($data[0]);
        foreach($data as $k => $info){
            array_push($cellData, array_values($info));
        }
        Excel::create('管理员信息表',function($excel) use ($cellData){
            $excel->sheet('信息库', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    /**
     * Send mail
     * @param array data 
     * @return rersponse
     */
    public function send(Request $request){
        //发送邮件邮件
        Mail::to($request->to)
            ->cc($request->cc)
            ->send(new MdEmail($order));
        Mail::to('fatlay@foxmail.com')->send(new MdEmail($request));
    }

    /**
     * 邮件情况 统计
     */
    private function mailStatistics(){
        //1、收件箱 2、发件箱 3、草稿箱 4、回收箱 5、垃圾箱
        $typeLists = ['outbox' => 1,'sentBox' => 2,'drafts' => 3,'junk' => 4,'trash' => 5];
        $v = [];
        foreach($typeLists as $k => $type){
            $count = SysUserMail::where('type', $type)->count();
            $v[$k][] = $count;
        }
        return $v;
    }

    /**
     * 邮件类型
     */
    public function allMailTypes(){
        $all = SysMailType::all();
        return $all;
    }
}
