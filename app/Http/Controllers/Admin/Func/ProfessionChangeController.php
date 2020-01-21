<?php

namespace App\Http\Controllers\Admin\Func;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Api\RpaFlow;
use App\Models\Admin\Func\RpaProfessionChange;
use Illuminate\Http\Request;
use Excel;

class ProfessionChangeController extends BaseAdminController
{
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "职业变更列表");
        return view('admin.func.profession.index');
    }

    /**
     * @param Request $request
     */
    public function pagination(Request $request){
        $params = $this->get_params($request, ['status', 'name', 'zjzh']);
        $condition = [
            ['rf.status', 1],
            ['rf.tid', 163]
        ];
        if(isset($params['status'])) {
            $condition[] = ['rpc.status','=' ,$params['status']];
        }
        if(isset($params['name'])) {
            $condition[] = ['rkh.name', 'like' , "%{$params['name']}%"];
        }
        if(isset($params['zjzh'])) {
            $condition[] = ['rkh.zjzh', '=', $params['zjzh']];
        }
        $rows = $request->rows;
        // $statusNameList =  [
        //     '1' => '登录',
        //     '3' => '申请完成',
        //     '4' => '变更成功',
        //     '5' => '变更失败',
        // ];
        $statusNameList =  [
            '1' => "<span class='x-tag x-tag-sm x-tag-default'>登录</span>",
            '3' => "<span class='x-tag x-tag-sm x-tag-info'>申请完成</span>",
            '4' => "<span class='x-tag x-tag-sm x-tag-success'>变更成功</span>",
            '5' => "<span class='x-tag x-tag-sm x-tag-danger'>变更失败</span>",
            '6' => "<span class='x-tag x-tag-sm x-tag-warning'>适当性户</span>",
        ];
        $professionList = $this->professionList();
        $result = RpaFlow::from('rpa_kh_flows as rf')
            ->where($condition)
            ->leftJoin('rpa_khs as rkh', 'rf.uid', '=', 'rkh.id')
            ->leftJoin('rpa_profession_change as rpc', 'rf.business_id', '=', 'rpc.id')
            ->orderBy('rf.id', 'asc')
            ->select(['rkh.name as name', 'rkh.zjzh as zjzh', 'rkh.phone as phone',
                'rkh.sfz as sfz', 'rpc.status as status', 'rpc.created_at as created_at',
                'rpc.profession_code as profession_code', 'rpc.handle_status as handle_status',
                'rpc.confirm as confirm', 'rpc.updated_at as updated_at', 'rpc.confirm_time as confirm_time','rpc.id as id'])
            ->paginate($rows);
        foreach($result as &$v) {
            $v->statusName = $statusNameList[$v->status];
            $v->profession = $professionList[$v->profession_code];
            $v->operation = $v->confirm?$v->confirm:'rpa';
        }
        return $result;
    }

    /**
     * 人工确认
     * @param Request $request
     * @return array
     */
    public function confirmOne(Request $request){
        $id = $request->id;
        $info = RpaProfessionChange::where('id', $id)->first();
        if(!$info) return $this->ajax_return(500, '找不到对应申请');
        if(!($info->status == 5 || $info->status == 6)) {
            return $this->ajax_return(500, '该用户状态无法被修改');
        }
        $info->status = 4;
        $info->handle_status = 3;
        $info->confirm_time = date('Y-m-d H:i:s');
        $info->updated_at = date('Y-m-d H:i:s');
        $info->confirm = auth()->guard('admin')->user()->realName;
        $result = $info->save();
        if($result) {
            return $this->ajax_return(200, '修改成功');
        } else {
            return $this->ajax_return(500, '修改失败');
        }
    }

    /**
     * 职业列表
     *
     */
    public function professionList(){
        $list = [
            '01'=>'文教科卫专业人员',
            '02'=>'党政 ( 在职，离退休 ) 机关干部',
            '03'=>'企事业单位干部',
            '04'=>'行政企事业单位工人',
            '05'=>'农民',
            '06'=>'个体',
            '08'=>'军人',
            '09'=>'学生',
            '10'=>'证券从业人员',
            '11'=>'离退休',
            '15'=>'国家机关、党群组织、企业、事业单位负责人',
            '16'=>'科学研究人员',
            '17'=>'信息技术、工程技术、农业技术、卫生专业技术人员',
            '18'=>'经济、金融业务人员',
            '19'=>'法律专业人员',
            '20'=>'教学人员，体育工作、新闻出版工作人员',
            '21'=>'安全保卫和消防人员',
            '22'=>'邮政和电信业务人员',
            '23'=>'交通运输、购销、仓储人员',
            '24'=>'餐饮、旅游服务人员',
            '25'=>'医疗卫生辅助服务、社会服务和居民生活服务人员',
            '26'=>'农、林、牧、渔、水利业生产人员',
            '27'=>'勘探、矿物开采、金属冶炼、轧制人员',
            '28'=>'机械制造加工、机械设备修理人员',
            '29'=>'电子元器件、机电产品及电力设备制造、装配、调试及维修人员',
            '30'=>'化工产品、橡胶及塑料制品生产人员',
            '31'=>'印染、纺织、缝纫人员，皮革制品加工制作人员',
            '32'=>'粮油、食品饮料、饲料生产加工人员',
            '33'=>'烟草及其制品加工人员、药品生产人员',
            '34'=>'木制品、纸制品、建筑材料、玻璃、陶瓷制品生产加工人员',
            '35'=>'广播影视作品、工艺美术品、文化体育用品制作人员，文物保护作业人员',
            '36'=>'文化工作、健身娱乐、珠宝业、博彩业、拍卖典当、艺术品收藏人员',
            '37'=>'废品收购工作人员',
            '38'=>'电子商务工作人员',
            '39'=>'离岸公司、国际组织工作人员',
            '40'=>'个体工商户、私营企业主',
            '41'=>'工程施工人员',
            '42'=>'环境监测与废物处理人员',
            '43'=>'检验、计量人员',
            '45'=>'企业中高级管理人员',
            '46'=>'党政机关、事业单位工作人员',
            '48'=>'企业职工',
            '49'=>'注册会计师',
            '50'=>'企业财务会计人员'
        ];
        return $list;
    }

    /**
     * 导出
     * @param Request $request
     * @return
     */
    public function export(Request $request){
        $params = $this->get_params($request, ['status', 'name', 'zjzh']);
        $condition = [
            ['rf.status', 1],
            ['rf.tid', 163]
        ];
        if(isset($params['status'])) {
            $condition[] = ['rpc.status','=' ,$params['status']];
        }
        if(isset($params['name'])) {
            $condition[] = ['rkh.name', 'like' , "%{$params['name']}%"];
        }
        if(isset($params['zjzh'])) {
            $condition[] = ['rkh.zjzh', '=', $params['zjzh']];
        }
        $statusNameList =  [
            '1' => "登录",
            '3' => "申请完成",
            '4' => "变更成功",
            '5' => "变更失败",
            '6' => "适当性客户",
        ];
        $professionList = $this->professionList();
        //设置需要导出的列，以及对应的表头
        $exportList = [
            'id' => 'ID',
            'name' => '客户名称',
            'zjzh' => '资金账号',
            'sfz' => '身份证号',
            'phone' => '手机号',
            'statusName' => '状态',
            'professionName' => '职业名称',
            'created_at' => '创建时间',
            'confirm_time' => '操作时间',
            'confirm' => '操作人',

        ];
        $cellData[] = array_values($exportList);

        $result = RpaFlow::from('rpa_kh_flows as rf')
            ->where($condition)
            ->leftJoin('rpa_khs as rkh', 'rf.uid', '=', 'rkh.id')
            ->leftJoin('rpa_profession_change as rpc', 'rf.business_id', '=', 'rpc.id')
            ->orderBy('rf.id', 'asc')
            ->select(['rkh.name as name', 'rkh.zjzh as zjzh', 'rkh.phone as phone',
                'rkh.sfz as sfz', 'rpc.status as status', 'rpc.created_at as created_at',
                'rpc.profession_code as profession_code', 'rpc.handle_status as handle_status',
                'rpc.confirm as confirm', 'rpc.confirm_time as confirm_time','rpc.id as id'])
            ->get()->toArray();
        foreach($result as &$v) {
            $v['statusName'] = $statusNameList[$v['status']];
            $v['professionName'] = isset($professionList[$v['profession_code']])?:'';


            $item = [];
            foreach ($exportList as $k => $export) {
                $item[] = (string)$v[$k];
            }
            array_push($cellData, $item);
        }
        
        $this->log(__CLASS__, __FUNCTION__, $request, "导出 职业申请 列表");
        Excel::create('职业申请列表',function($excel) use ($cellData){
            $excel->sheet('职业申请列表', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');

        return $result;
    }
}
