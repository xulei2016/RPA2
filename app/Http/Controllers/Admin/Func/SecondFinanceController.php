<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Api\RpaShixincfa;
use App\Models\Admin\Api\RpaShixinhss;
use App\Models\Admin\Api\RpaShixinsf;
use App\Models\Admin\Api\RpaShixinxyzg;
use App\Models\Admin\Func\RpaCustomerSecondFinance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SecondFinanceController extends BaseAdminController {

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin/func/SecondFinance/index');
    }

    /**
     * 分页
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request){
        $selectInfo = $this->get_params($request, ['fundsNum','status','from_open_date','to_open_date']);

        $condition = $this->getPagingList($selectInfo, ['from_open_date'=>'>=','to_open_date'=>'<=']);
        $fundsNum = $selectInfo['fundsNum'];
        if($selectInfo['status']){
            array_push($condition,array('status','=',$selectInfo['status']));
        }
        if($fundsNum && is_numeric($fundsNum)){
            array_push($condition,  array('fundsNum', 'like', "%".$fundsNum."%"));
        }elseif(!empty( $customer )){
            array_push($condition,  array('name', 'like', "%".$customer."%"));
        }

        $rows = $request->rows;
        $order = $request->sort ?? 'created_at';
        $sort = $request->sortOrder ?? 'desc';
        $list = RpaCustomerSecondFinance::where($condition)->orderBy($order,$sort)->orderBy('id','desc')->paginate($rows);
        foreach ($list as &$v) {
            //查询失信
            $date = $v->open_date." 23:59:59";
            $yesterday = date('Y-m-d',strtotime("-1 day",strtotime($v->open_date)))." 15:00:00";
            $zq = RpaShixinsf::where([['name',$v->name],['idnum',$v->idCard],["updatetime",'>=',$yesterday],["updatetime",'<=',$date]])->orderBy('updatetime','desc')->first();
            $qh = RpaShixincfa::where([['name',$v->name],['idnum',$v->idCard],["updatetime",'>=',$yesterday],["updatetime",'<=',$date]])->orderBy('updatetime','desc')->first();
            $hs = RpaShixinhss::where([['name',$v->name],['idnum',$v->idCard],["updatetime",'>=',$yesterday],["updatetime",'<=',$date]])->orderBy('updatetime','desc')->first();
            $xyzg = RpaShixinxyzg::where([['name',$v->name],['idnum',$v->idCard],["updatetime",'>=',$yesterday],["updatetime",'<=',$date]])->orderBy('updatetime','desc')->first();
            if(!isset($zq->state) || $zq->state != 0 || !isset($qh->state) && $qh->state != 0 || !isset($hs->state) && $hs->state != 0 || !isset($xyzg->state) && $xyzg->state != 0){
                $v->status = -1;
            }
        }
        return $list;
    }

    /**
     * 上报
     * @param Request $request
     * @return array
     */
    public function report(Request $request)
    {
        $id = $request->id;
        $res = RpaCustomerSecondFinance::where('id',$id)->update(['status' => 2]);
        if($res){
            return $this->ajax_return(200, '成功');
        }else{
            return $this->ajax_return(500, '失败');
        }
    }

    /**
     * 查看截图
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request,$id)
    {
        $res = RpaCustomerSecondFinance::where('id',$id)->first();
        $xyzgpaths = explode(",",$res->xyzgpaths);
        $list = [
            ['name' => '中金所会服截图','url' => $res->jkzxpaths],
            ['name' => '信用中国失信截图一','url' => $xyzgpaths[0]],
            ['name' => '信用中国失信截图二','url' => $xyzgpaths[1]],
            ['name' => '身份证正面照','url' => $res->sfz_zm],
            ['name' => '身份证反面照','url' => $res->sfz_fm],
            ['name' => '证券失信截图','url' => $res->sfpaths],
            ['name' => '期货失信截图','url' => $res->cfapaths],
            ['name' => '恒生黑名单截图','url' => $res->hspaths],
        ];
        return view('admin/func/SecondFinance/show',['list' => $list]);
    }

    /**
     * 显示图片
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function showImg(Request $request)
    {
        $url = $request->get('url');
        $url = Crypt::decrypt($url);
        return new BinaryFileResponse($url);
    }

    /**
     * 文件下载
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function download(Request $request)
    {
        $id = $request->id;
        $user = RpaCustomerSecondFinance::where('id',$id)->first();
        $root = dirname($user->jkzxpaths);
        //1.创建并打开压缩包
        $zip = new \ZipArchive();
        $name = $user->name."_".$user->fundsNum.".zip";
        $zip->open($name,\ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        //2.向压缩包添加文件
        $options = [
            'remove_path' => $root
        ];
        $zip->addGlob($root."/*.*",GLOB_BRACE,$options);
        //3.关闭压缩包
        $zip->close();
        //4.输出
        return response()->download($name)->deleteFileAfterSend(true);
    }
}