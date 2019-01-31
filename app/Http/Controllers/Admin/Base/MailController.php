<?php

namespace App\Http\Controllers\admin\base;

use App\models\admin\base\SysMail;
use App\Mail\MdEmail;
use App\Models\Admin\Admin\SysAdmin;
use App\Models\Admin\Admin\SysAdminGroup;
use App\Models\Admin\Base\SysMailType;
use App\Models\Admin\Base\SysMailMode;
use App\Models\Admin\Base\SysMessageObjects;
use App\Models\Admin\Base\SysMessageTypes;
use App\Models\Admin\Base\SysRole;
use App\Models\Admin\Base\SysUserMail;
use App\Models\Admin\Base\SysMailOutbox;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function create(Request $request)
    {
        //发送类型
        $types = SysMessageTypes::all();
        //发送对象
        $object = SysMessageObjects::orderBy('id','desc')->get();
        $this->log(__CLASS__, __FUNCTION__, $request, "发布邮件 页面");
        return view('admin.base.mail.send', ['types' => $types, 'object' => $object]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "发送 邮件");
        $data = [
            'title' => $request->project,
            'content' => $request->editor,
            'tid' => $request->type
        ];
        // 获取发送邮件的用户和id
        $admin = $this->getAdmin($request->mode,$request->user);
        $sysAdminIds = $admin['sysAdminIds'];
        $sysAdmin = $admin['sysAdmin'];

        //如果存在附件
        if($request->file('attachment')){
            $allow_ext = [
                'RAR','zip','pdf','xls','txt','doc','gif','jpg','png','jpeg'
            ];
            $data['file_path'] = $this->uploadFile($request->file('attachment'),'mail',$allow_ext);

            if(!$data['file_path']){
                return $this->ajax_return('500', '附件类型不允许发送!!！');
            }
        }

        $sysmail = SysMail::create($data);
        if($sysmail){
            // 给自己添加发件记录
            $data = [
                'mid' => $sysmail->id,
                'uid' => Auth::user()->id,
                'type' => 2
            ];
            SysUserMail::create($data);

            $sysmail->admins()->attach($sysAdminIds);
            Mail::to($sysAdmin)->send(new MdEmail($sysmail));
            return $this->ajax_return('200', '操作成功！');
        }else{
            return $sysmail;
        }
    }

    public function draft(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "草稿 邮件");
        $data = [
            'title' => $request->project,
            'content' => $request->editor,
            'tid' => $request->type
        ];

        //如果存在附件
        if($request->file('attachment')){
            $allow_ext = [
                'RAR','zip','pdf','xls','txt','doc','gif','jpg','png','jpeg'
            ];
            $data['file_path'] = $this->uploadFile($request->file('attachment'),'mail',$allow_ext);

            if(!$data['file_path']){
                return $this->ajax_return('500', '附件类型不允许发送!!！');
            }
        }

        $sysmail = SysMail::create($data);
        if($sysmail) {
            // 给自己添加草稿记录
            $data = [
                'mid' => $sysmail->id,
                'uid' => Auth::user()->id,
                'type' => 3
            ];
            SysUserMail::create($data);
            return $this->ajax_return('200', '操作成功！');
        }else{
            return $sysmail;
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SysMail $sysMail)
    {
        //设置已读
        $uid = Auth::user()->id;
        $mid = $sysMail->id;
        SysUserMail::where([['uid','=',$uid],['mid','=',$mid]])->update(['read_at'=>self::getTime()]);

        return view('admin.base.mail.show', ['sysMail' => $sysMail]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SysMail $sysMail)
    {
        //发送类型
        $types = SysMessageTypes::all();
        //发送对象
        $object = SysMessageObjects::orderBy('id','desc')->get();
        return view('admin.base.mail.edit',['types' => $types,'object' => $object,'sysMail' => $sysMail]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reSend(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "重新发送 邮件");
        $id = $request->id;
        $data = [
            'title' => $request->project,
            'content' => $request->editor,
            'tid' => $request->type
        ];
        // 获取发送邮件的用户和id
        $admin = $this->getAdmin($request->mode,$request->user);
        $sysAdminIds = $admin['sysAdminIds'];
        $sysAdmin = $admin['sysAdmin'];

        //如果存在附件
        $sysmail = SysMail::find($id);
        if($request->file('attachment') && $request->attachment != $sysmail->file_path){
            $allow_ext = [
                'RAR','zip','pdf','xls','txt','doc','gif','jpg','png','jpeg'
            ];
            $data['file_path'] = $this->uploadFile($request->file('attachment'),'mail',$allow_ext);

            if(!$data['file_path']){
                return $this->ajax_return('500', '附件类型不允许发送!!！');
            }
        }

        $sysmail = $sysmail->update($data);
        if($sysmail){
            $sysmail = SysMail::find($id);
            // 将草稿箱改为发件箱
            SysUserMail::where([['uid','=',Auth::user()->id],['mid','=',$sysmail->id]])->update(['type' => 2]);

            $sysmail->admins()->attach($sysAdminIds);
            Mail::to($sysAdmin)->send(new MdEmail($sysmail));
            return $this->ajax_return('200', '操作成功！');
        }else{
            return $sysmail;
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
        $uid = Auth::user()->id;
        SysUserMail::where([['uid','=',$uid],['mid','=',$id]])->update(['type'=>4]);
        return $this->ajax_return('200', '操作成功！');
    }
    
    /**
     * pagenation
     */
    public function pagenation(Request $request){
        $rows = $request->rows;
        $data = $this->get_params($request, ['title','type']);
        $data['uid'] = Auth::user()->id;
        $conditions = $this->getPagingList($data, ['uid'=>'=', 'type'=>'=']);
        $order = $request->sort ?? 'mid';
        $sort = $request->sortOrder;
        $result = SysUserMail::where($conditions)->orderBy($order,$sort)->with('mails')->paginate($rows);
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
     * 未读邮件情况 统计
     */
    private function mailStatistics(){
        //1、收件箱 2、发件箱 3、草稿箱 4、回收箱
        $typeLists = self::mailTypeList();

        $uid = Auth::user()->id;
        foreach($typeLists as &$type){
            $count = SysUserMail::where([['type','=', $type['id']],['read_at','=', ''],['uid', '=', $uid]])->count();
            $type['count'] = $count;
        }
        return $typeLists;
    }

    /**
     * 邮箱类型列表
     */
    private function mailTypeList(){
        return [
            [
                'id' => 1,
                'name' => 'outbox',
                'desc' => '收件箱',
                'icon' => 'fa-inbox'
            ],
            [
                'id' => 2,
                'name' => 'sentBox',
                'desc' => '发件箱',
                'icon' => 'fa-envelope-o'
            ],
            [
                'id' => 3,
                'name' => 'drafts',
                'desc' => '草稿箱',
                'icon' => 'fa-file-text-o'
            ],
            [
                'id' => 4,
                'name' => 'trash',
                'desc' => '回收箱',
                'icon' => 'fa-trash-o'
            ]
        ];
    }
// 获取发送邮件的用户和id
    public function getAdmin($mode,$user)
    {
        $sysAdminIds = [];
        $sysAdmin = [];
        if(4 == $mode){
            $sysAdmins = SysAdmin::where("type",1)->get();
            foreach($sysAdmins as $admin){
                $sysAdminIds[] = $admin->id;
                $sysAdmin[] = $admin;
            }
        }else if(3 == $mode){
            $role_ids = $user;
            $roles = SysRole::whereIn('id',$role_ids)->get();
            foreach($roles as $role){
                foreach($role->users->where("type",1) as $admin){
                    $sysAdminIds[] = $admin->id;
                    $sysAdmin[] = $admin;
                }
            }
        }else if(2 == $mode){
            $group_ids = $user;
            $groups = SysAdminGroup::whereIn('id',$group_ids)->get();
            foreach($groups as $group){
                foreach($group->users->where("type",1) as $admin){
                    $sysAdminIds[] = $admin->id;
                    $sysAdmin[] = $admin;
                }
            }
        }else if(1 == $mode){
            $admin_ids = $user;
            $sysAdmins = SysAdmin::whereIn('id',$admin_ids)->get();
            foreach($sysAdmins as $admin){
                $sysAdminIds[] = $admin->id;
                $sysAdmin[] = $admin;
            }
        }
        return [
            'sysAdminIds'=>$sysAdminIds,
            'sysAdmin' => $sysAdmin
        ];
    }
}
