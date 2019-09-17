<?php

namespace App\Http\Controllers\Admin\Base\Document;

use Illuminate\HTTP\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Admin\Base\Document\SysDocumentMenu;
use App\Models\Admin\Base\Document\SysDocumentContent;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * DocumentController
 * @author lay
 * @since 2019-09-03
 */
class DocumentController extends BaseAdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->log(__CLASS__, __FUNCTION__, $request, "查阅文档");
        return view('Admin.Base.Document.index');
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
        $data = $this->get_params($request, ['name','parent_id']);
        $result = SysDocumentMenu::create($data);

        $this->log(__CLASS__, __FUNCTION__, $request, "添加结点");
        return $this->ajax_return('200', '操作成功！', $result);
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
        $data = $this->get_params($request, ['name','moveType','objId','content','type','formData','upfile'], false);
        //是否排序组合
        if(isset($data['moveType'])){
            $moveType = $data['moveType'];
            $obj = SysDocumentMenu::where('id', $data['objId'])->first();
            $data = [];
            switch($moveType){
                case 'inner':
                    $data['parent_id'] = $obj['id'];
                    $data['order'] = 0;
                break;
                case 'prev': 
                    $data['parent_id'] = $obj['parent_id'];
                    $data['order'] = $obj['order'];
                    SysDocumentMenu::where([['parent_id','=',$obj['parent_id']],['order','>=',$obj['order']]])->increment('order');
                break;
                case 'next': 
                    $data['parent_id'] = $obj['parent_id'];
                    $data['order'] = $obj['order']+1;
                    SysDocumentMenu::where([['parent_id','=',$obj['parent_id']],['order','>',$obj['order']]])->increment('order');
                break;
            }
        }
        if(isset($data['type']) && 'doc' == $data['type']){
            unset($data['type']);
            if(isset($data['upfile'])){ //是否携带附件
                foreach($data['upfile'] as $file){
                    $files = [];
                    //判断文件是否上传成功
                    if ($file->isValid()) {
                        //获取原文件名
                        $files['originalName'] = $file->getClientOriginalName();
                        //文件尺寸
                        $files['size'] = $file->getSize();
                        //扩展名
                        $ext = $file->getClientOriginalExtension();
                        //文件类型
                        $type = $file->getClientMimeType();
                        $files['type'] = substr($files['originalName'],strripos($files['originalName'],".")+1);
                        //临时绝对路径
                        $realPath = $file->getRealPath();
                        $files['filename'] = '/public/storage/doc/files/'.date('Ym') . '/' . uniqid() . '.' . $ext;
                        $bool = Storage::disk('local')->put($files['filename'], file_get_contents($realPath));
                    }
                    $data['uploads'][] = $files;
                }
                // $data['uploads'] = json_encode($data['uploads'],JSON_UNESCAPED_UNICODE);
                unset($data['upfile']);
            }
            // $content_exists = SysDocumentContent::find($id);
            $content_exists = SysDocumentContent::where('did',$id)->first();
            if($content_exists){
                $content = $content_exists;
                $file = isset($content['uploads']) ? json_decode($content['uploads'], true) : [] ; 
                if($file){
                    $data['uploads'] = array_merge($file, $data['uploads']);
                    $data['uploads'] = json_encode($data['uploads'],JSON_UNESCAPED_UNICODE);
                }
                $result = SysDocumentContent::where('did',$id)->update($data);
            }else{
                $creater_id = auth()->guard()->user()->id;
                $data['did'] = $id;
                $data['creater_id'] = $creater_id;
                $result = SysDocumentContent::create($data);
            }
        }else{
            $result = SysDocumentMenu::where('id',$id)->update($data);
        }

        $this->log(__CLASS__, __FUNCTION__, $request, "编辑结点");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $hasChild = SysDocumentMenu::where('parent_id',$id)->first();
        if($hasChild){
            return $this->ajax_return('500', '操作失败，该节点拥有子节点！');
        }
        $result = SysDocumentMenu::where('id',$id)->delete();

        $this->log(__CLASS__, __FUNCTION__, $request, "删除文档");
        return $this->ajax_return('200', '操作成功！');
    }

    /**
     * Get all menus
     * 
     * @param
     */
    public function getAllMenus(Request $request){
        $menus = SysDocumentMenu::orderBy('order','asc')
                ->get(['id','parent_id as pid','name'])
                ->toArray();
        $menus[0]['open'] = true;
        $menus[1]['open'] = true;
        return $this->ajax_return('200', '查询成功！', $menus);
    }

    /**
     * Get getDoc
     * 
     * @param
     */
    public function getDoc(Request $request, $id){
        $content = SysDocumentContent::where('did',$id)
        ->leftJoin('sys_admins', 'sys_admins.id', '=', 'sys_document_contents.creater_id')
        ->select(['sys_document_contents.*', 'sys_admins.name as creater_name'])
        ->first();
        $content ? ($content['uploads'] = json_decode($content['uploads'],true)) : $content;
        return $this->ajax_return('200', '查询成功！', $content);
    }

    /**
     * POST deleteDoc
     * 
     * @param
     */
    public function deleteDoc(Request $request, $id){
        $fileName = $request->file;
        $content = SysDocumentContent::where('id',$id)->first();
        $files = json_decode($content['uploads'],true);
        $type = false;
        foreach($files as $k => $file){
            if($fileName == $file['originalName']){
                $disk = Storage::disk('local');
                // $exists = $disk->exists(str_replace('\\','',$file['filename']));
                if($disk->delete(str_replace('\\','',$file['filename']))){
                    unset($files[$k]);
                    $type = true;
                    break;
                };
            }
        }
        if($type){
            $files = array_values($files);//还原下标，转化为索引数组
            SysDocumentContent::where('id',$id)->update(['uploads'=>json_encode($files,JSON_UNESCAPED_UNICODE)]);
            $this->log(__CLASS__, __FUNCTION__, $request, "更新附件");
            return $this->ajax_return('200', '删除成功！');
        }
        return $this->ajax_return('500', '删除失败！');
    }
    
    /**
     * 文件上传
     */
    public function uploadFiles(Request $request) {
        $file = $request->file('upload');
        $file = $this->upload($file);
        if($file){
            return ['uploaded'=>1,'url'=>$file];
        }
        return ['uploaded'=>0,'error'=>['message'=>'上传失败！']];
    }

    /**
     * 验证文件是否合法
     */
    public function upload($file, $disk='local') {
        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if(!in_array($fileExtension, ['gif','jpeg',"jpg", "png", "rar", "txt", "zip", "doc", "ppt", "xls", "pdf", "docx", "xlsx"])) {
            return false;
        }

        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 2048000) {
            return false;
        }

        // 4.是否是通过http请求表单提交的文件
        if (!is_uploaded_file($tmpFile)) {
            return false;
        }

        // 5.每月一个文件夹,分开存储, 生成一个随机文件名
        $fileName = '/public/doc/images/'.date('Y_m').'/'.md5(time()) .mt_rand(0,9999).'.'. $fileExtension;
        if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile)) ){
            return Storage::url($fileName);
        }
    }
}
