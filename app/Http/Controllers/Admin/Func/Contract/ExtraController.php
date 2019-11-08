<?php

namespace App\Http\Controllers\Admin\Func\Contract;

use App\Models\Admin\Func\Contract\RpaContractDetail;
use App\Models\Admin\Func\Contract\RpaContractPublish;
use App\Models\Admin\Func\Contract\RpaContractPublishExtra;
use App\Models\Admin\Func\Contract\RpaContractDict;
use App\Models\Admin\Func\Contract\RpaContractJys;
use App\Models\Admin\Func\Contract\RpaContractPz;
use Illuminate\Http\Request;

/**
 * 合约 指定日期推送
 * Class ExtraController
 * @package App\Http\Controllers\Admin\Func\Contract
 */
class ExtraController extends BaseController
{
    /**
     * @var string 页面前缀
     */
    protected $view_prefix = "admin.func.contract.extra.";

    /**
     * 首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "合约-指定交易日 列表页");
        $jys = RpaContractJys::get();
        return view($this->view_prefix.'index', ['jys' => $jys]);
    }

    /**
     * 数据
     * @param Request $request
     * @return
     */
    public function pagination(Request $request) {
        $selectInfo = $this->get_params($request, ['jys_id', 'category']);
        $condition = $this->getPagingList($selectInfo, ['jys_id' => '=', 'category' => '=']);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'asc';
        $condition[] = [
            'category', '=', '1'
        ];
        $list = RpaContractPublishExtra::where($condition)->orderBy($order, $sort)->paginate($rows);
        foreach ($list as &$v) {
            $jys = RpaContractJys::where('id', $v->jys_id)->first();
            $pz = RpaContractPz::where('id', $v->pz_id)->first();
            $v->jys = $jys?$jys->name:'暂无';
            $v->pz = $pz?$pz->name:'暂无';
        }
        return $list;
    }

    /**
     * 新增页
     */
    public function create()
    {
        $jys = RpaContractJys::get();
        return view($this->view_prefix.'add', ['jys' => $jys]);
    }

    /**
     * 新增保存
     * @param Request $request
     * @return array
     */
    public function store(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "新增 合约-指定日期");
        $flag = false; 
        $data = $this->get_params($request, [
            'jys_id','pz_id','date','hydm'
        ]);
        $pz = RpaContractPz::where('id', $data['pz_id'])->first();
        $jys = RpaContractJys::where('id', $data['jys_id'])->first();
        $hydm = $pz->code.$data['hydm'];
        $condition[] = ['hydm', '=', $hydm];
        $list = RpaContractPublishExtra::where($condition)->first();
        if($list) return $this->ajax_return(500, '该合约已经指定过日期');
        $data['created_by'] = auth()->guard()->user()->id;
        $data['updated_by'] = auth()->guard()->user()->id;
        $data['real_date'] = $data['date'];
        RpaContractPublishExtra::where('hydm', $hydm)->delete();
        $number = RpaContractDict::where('name', 'DAY_NUMBER')->first()->value;
        $this->dayList = $this->getAllDays();
        $dateList = $this->returnDays($data['date'], $number, $jys->code);
        $data['category'] = 1;
        $data['hydm'] = $hydm;
        $r = RpaContractPublishExtra::create($data);
        if(!$r) $flag = true;
        foreach($dateList['extra'] as $v) {
            $data['category'] = 2;
            $data['date'] = date('Y-m-d', strtotime($v));
            $r = RpaContractPublishExtra::create($data);
            if(!$r) $flag = true;
        }
        if($flag) {
            return $this->ajax_return(500, '保存失败！');
        } else {
            return $this->ajax_return(200, '操作成功！');
        }
    }

    /**
     * 编辑页
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id){
        $jys = RpaContractJys::get();
        $info = RpaContractPublishExtra::where('id', $id)->first();
        $pz = RpaContractPz::where('jys_id', $info->jys_id)->get();
        return view($this->view_prefix.'edit', ['info' => $info, 'jys' => $jys, 'pz' => $pz]);
    }

    /**
     * 更新数据
     * @param Request $request
     * @return array
     */
    public function update(Request $request){
        $data = $this->get_params($request, [
            'id','jys_id','pz_id','date','hydm'
        ]);
        $flag = false; 
        $pz = RpaContractPz::where('id', $data['pz_id'])->first();
        $jys = RpaContractJys::where('id', $data['jys_id'])->first();
       
        $extra = RpaContractPublishExtra::where('id', $data['id'])->first();
        $data['updated_by'] = auth()->guard()->user()->id;
        $data['hydm'] = $extra->hydm;
        $data['real_date'] = $data['date'];
        $result = RpaContractPublishExtra::where('id', $data['id'])->update($data);
        RpaContractPublishExtra::where([
            ['hydm', '=', $extra->hydm],
            ['category', '=', 2]
        ])->delete();
        $number = RpaContractDict::where('name', 'DAY_NUMBER')->first()->value;
        $this->dayList = $this->getAllDays();
        $dateList = $this->returnDays($data['date'], $number, $jys->code);
        $data['category'] = 1;
        $data['created_by'] = auth()->guard()->user()->id;
        unset($data['id']);
        foreach($dateList['extra'] as $v) {
            $data['category'] = 2;
            $data['date'] = date('Y-m-d', strtotime($v));
            $r = RpaContractPublishExtra::create($data);
            if(!$r) $flag = true;
        }
        if($flag) {
            return $this->ajax_return(500, '保存失败！');
        } else {
            return $this->ajax_return(200, '操作成功！');
        }
    }

    /**
     * 删除
     * @param Request $request
     * @param $ids
     */
    public function destroy(Request $request, $ids){
        $ids = explode(',', $ids);
        foreach($ids as $id) {
            $extra = RpaContractPublishExtra::where('id', $id)->first();
            RpaContractPublishExtra::where('hydm', $extra->hydm)->delete();
        }
        $this->log(__CLASS__, __FUNCTION__, $request, "删除 合约-指定日期");
        return $this->ajax_return('200', '操作成功');
    }

    

 
}