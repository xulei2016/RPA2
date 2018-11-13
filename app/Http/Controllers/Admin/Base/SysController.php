<?php

namespace App\Http\Controllers\admin\base;

use Illuminate\Http\Request;
use App\Http\Controllers\Base\BaseAdminController;

/**
 * SysController
 * @author lay
 * @since 2018-10-25
 */
class SysController extends BaseAdminController
{
    /**
     * dashboard
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");
        return view('admin.index.index');
    }
    
    /**
     * 主页
     */
    public function get_index(Request $request){
        $admin = session('sys_admin');
        $info = $this->sys_info();
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 首页");
        return view('admin/index/dashboard', ['info'=>$info,'admin'=>$admin]);
    }

    /**
     * 系统管理
     */
    public function sysConfig(Request $request){
        $condition = [['type','<>','hidden']];
        $config = $this->model->findAllBy($condition,['sort', 'asc']);
        $this->log(__CLASS__, __FUNCTION__, $request, "系统管理页面");
        return view('admin/sys/config/list',['config'=>$config['data']]);
    }

    /**
     * 头像列表
     */
    public function headImgList(Request $request){
        return $this->img->all();
    }

    /**
     * 修改系统管理
     */
    public function update(Request $request){
        $data = $request->all();
        //是否重新上传图片
        if(!empty($_FILES['logo'])){
            $thumb = $this->model->utilUploadPhotoJust('logo', 'images/common/logo/',120,120);
            if(!$thumb){
                return $this->ajax_return('500', '图片保存失败！');
            }
            $data['logo'] = $thumb;
            $this->unlinkImg($request->prevurl);
        }
        foreach($data as $k => $val){
            $this->model->update(['item_value'=>$val], $k, 'item_key');
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "修改系统设置");
        return $this->ajax_return(200, '更新成功！');
    }

    /**
     * 上传头像
     */
    public function addImg(Request $request){
        if($request->imgUrl){
            $thumb = $this->img->utilUploadPhoto('imgUrl', 'images/admin/headImg/',120,120);
            if(!$thumb){
                return $this->ajax_return('500', '图片保存失败！',$thumb);
            }
            $data['url'] = $thumb['url'];
            $data['thumb'] = $thumb['thumb'];
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "上传头像");
        return $this->img->create($data);
    }

    //delete one
    public function del_img(Request $request){
        $id = $request->id;
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 头像 信息");
        return $this->img->delete($id);
    }

    /**
     * 清除缓存
     */
    public function clean_cache(){
        if($this->del_cache('sys_info') && $this->del_cache('sys_admin')){
            $this->authCacheInfo(false);
            return $this->ajax_return(200, '缓存清除成功！');
        }else{
            return $this->ajax_return(500, '缓存清除失败！请联系管理员处理');
        };
    }

    /**
     * 控制面板
     */
    public function dashboard(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 控制面板");
        return view('admin/admin/admin/index');
    }

    /**
     * 未知路由
     */
    public function notAllow(){
        return view('errors/noAllow');
    }

    /**
    * 系统
    */
    private function sys_info(){
        $info['sys'] = [
            '设备信息'      => php_uname() ,
            '协议'          => $_SERVER['SERVER_PROTOCOL'] ,
            'PHP 版本'      => 'PHP/'.PHP_VERSION ,
            'Laravel 版本'  => app()->version() ,
            'CGI'           => php_sapi_name() ,
            '服务'          => array_get($_SERVER, 'SERVER_SOFTWARE') ,
            '服务允许上传最大文件'  => get_cfg_var ("upload_max_filesize")?get_cfg_var ("upload_max_filesize"):"不允许上传附件" ,

            '缓存驱动'      => config('cache.default') ,
            'Session驱动'   => config('session.driver') ,
            '队列驱动'      => config('queue.default') ,

            '时区'          => config('app.timezone') ,
            'Locale'        => config('app.locale') ,
            'Env'           => config('app.env') ,
            'URL'           => config('app.url') ,
        ];
        $con = mysqli_connect(env('DB_HOST'),env('DB_USERNAME'),env('DB_PASSWORD'),env('DB_DATABASE'));
        $info['database'] = [
            '数据库版本'    => mysqli_get_server_info($con),
        ];
        // exec("wmic LOGICALDISK get name,Description,filesystem,size,freespace",$info['disk']);

        return $info;
    }

    
    /**
     * 400
     */
    public function error400(Request $request){
        return view('errors.400');
    }
    
    /**
     * 401
     */
    public function error401(Request $request){
        return view('errors.401');
    }
    
    /**
     * 402
     */
    public function error402(Request $request){
        return view('errors.402');
    }
    
    /**
     * 403
     */
    public function error403(Request $request){
        return view('errors.403');
    }
    
    /**
     * 404
     */
    public function error404(Request $request){
        return view('errors.404');
    }
    
    /**
     * 500
     */
    public function error500(Request $request){
        return view('errors.500');
    }
}
