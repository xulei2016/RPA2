<?php


namespace App\Http\Controllers\Admin\Func;


use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Func\Plugin\RpaPlugin;
use App\Models\Admin\Func\Plugin\RpaPluginVersion;
use App\Models\Admin\Func\Plugin\RpaPluginApply;
use App\Models\Admin\Func\Plugin\RpaPluginDownload;
use App\User;
use Illuminate\Http\Request;

/**
 * 插件下载
 * Class DownloadPluginController
 * @package App\Http\Controllers\Admin\Func
 */
class DownloadPluginController extends BaseAdminController
{
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 插件下载 页");
        $list = RpaPlugin::get()->toArray();
        $user_id = (int) auth()->guard('admin')->user()->id; // 登录用户id
        foreach ($list as $k => $v) {
            $download = 0;
            $count = RpaPluginApply::where([
                ['uid', '=',$user_id],
                ['pid', '=', $v['id']],
                ['status', '=', '2']
            ])->count();
            if($count) $download = 1;
            $list[$k]['download'] = $download;
        }
        return view('admin/func/DownloadPlugin/index', ['list' => $list]);
    }


    /**
     * 客户申请
     * @param Request $request
     * @return array
     */
    public function apply(Request $request){
        $id = $request->id; //插件id
        $user_id = (int) auth()->guard('admin')->user()->id; // 登录用户id
        $count = RpaPluginApply::where([
            ['uid', '=',$user_id],
            ['pid', '=', $id]
        ])->whereIn('status', [1, 2])->count();
        if($count) {
            return $this->ajax_return(500, '你已经申请下载该插件, 无须重复申请');
        } else {
            $result = RpaPluginApply::create([
                'uid' => $user_id,
                'pid' => $id,
                'status' => 1,
            ]);
            if($result) {
                return $this->ajax_return(200, '成功');
            } else {
                return $this->ajax_return(500, '申请失败');
            }
        }
    }

    /**
     * 查询页面
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id) {
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 插件版本 页");
        $versions = RpaPluginVersion::where(["status" => 1,'pid' => $id])->orderBy('id', 'desc')->get();
        return view('admin/func/DownloadPlugin/show', ['versions' => $versions]);
    }

    /**
     * 下载页面
     * @param Request $request
     * @param $id
     * @return array|string
     */
    public function download(Request $request, $id){
        $this->log(__CLASS__, __FUNCTION__, $request, "插件下载");
        $version = RpaPluginVersion::where('id', $id)->first();
        $name = auth()->guard('admin')->user()->name;
        $user_id = auth()->guard('admin')->user()->id;
        $email = $name.'@BrowserExt.com';
        $user = User::where('email', $email)->first();
        if(!$version){
            return $this->ajax_return(500, '找不到对应的数据');
        }
        RpaPluginDownload::create([
            'uid' => $user_id,
            'version_id' => $version->id,
            'plugin_id' => $version->pid,
        ]);
        $dir = base_path();
        $copy = $dir."/storage/app/plugins/copy{$user_id}.zip";
        $filename = $dir."/storage/app/".$version->url;
        copy($filename, $copy);
        $real = pathinfo($version->show_name);
        $str = $this->getConfigByUser($user);
        $zip = new \ZipArchive();
        $zip->open($copy);
        $zip->addFromString($real['filename']."/js/config.js", $str);
        $zip->close();
        $this->downloadZip($copy, $real['basename']);
        return $str;
    }

    /**
     * 下载zip
     * @param $realName
     * @param $filename
     */
    public function downloadZip($realName, $filename){
        //打开文件
        $file = fopen($realName,"r");
        //返回的文件类型
        Header("Content-type: application/octet-stream");
        //按照字节大小返回
        Header("Accept-Ranges: bytes");
        //返回文件的大小
        Header("Accept-Length: ".filesize($realName));
        //这里对客户端的弹出对话框，对应的文件名
        Header("Content-Disposition: attachment; filename=".$filename);
        //修改之前，一次性将数据传输给客户端
        echo fread($file, filesize($realName));
        //修改之后，一次只传输1024个字节的数据给客户端
        //向客户端回送数据
        $buffer=1024;//
        //判断文件是否读完
        while (!feof($file)) {
            //将文件读入内存
            $file_data=fread($file,$buffer);
            //每次向客户端回送1024个字节的数据
            echo $file_data;
        }
        fclose($file);
        unlink($realName);
    }

    /**
     * 获取config文件信息
     * @param $user
     * @return string
     */
    public function getConfigByUser($user){
        $password = \Illuminate\Support\Facades\Crypt::decrypt($user->password);
        $str = 'var ojson = {
            grant_type: "password",
            client_id: 2,
            client_secret: "DuNMC6w9faxgeRx1g1eTC5N3lGvukbNiERAI7Jya",
            password: "'.($password).'",
            username: "'.($user->email).'",
            scope: ""
        };';
        return $str;
    }
}