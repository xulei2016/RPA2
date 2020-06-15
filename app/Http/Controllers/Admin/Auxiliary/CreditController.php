<?php


namespace App\Http\Controllers\Admin\Auxiliary;

use App\Http\Controllers\base\BaseAdminController;
use App\Models\Admin\Rpa\rpa_jjrcredit_sxstates;
use App\Models\Index\Common\FuncLostCreditRecord;
use App\Models\Index\Common\FuncLostCreditShowRecord;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CreditController extends BaseAdminController
{

    private $viewPrefix = "admin.Auxiliary.Credit.";

    /**
     * 图片类型
     * @var array
     */
    private $imageTypeList = [
        'sf' => '证券业失信截图',
        'cfa' => '期货业失信截图',
        'xyzg' => '信用中国截图',
        'hs' => '恒生黑名单截图',
        'zxgk' => '执行信息公开网截图',
        'gsxt' => '国家企信网截图',
    ];

    /**
     * 类型列表
     * @var array
     */
    private $typeList = [
        'person' => '个人',
        'company' => '企业',
        'legalPerson' => '法人',
        'agentPerson' => '授权代理人',
    ];

    /**
     * 首页
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return view($this->viewPrefix.'index');
    }

    /**
     * 分页数据
     * @param Request $request
     * @return mixed
     */
    public function pagination(Request $request)
    {
        $selectInfo = $this->get_params($request, ['name', 'idCard', 'code', 'type', 'date', 'status']);
        $condition = $this->getPagingList($selectInfo, [
            'name' => '=',
            'idCard' => '=',
            'code' => '=',
            'type' => '=',
            'date' => '=',
            'status' => '=',
        ]);
        $rows = $request->rows;
        $order = $request->sort ?? 'id';
        $sort = $request->sortOrder ?? 'desc';
        $list = FuncLostCreditRecord::where($condition)
            ->orderBy($order, $sort)
            ->paginate($rows);
        foreach ($list as &$v) {
            $v->idCard = substr_replace($v->idCard, "********", 6, 8);
        }
        return $list;
    }

    /**
     * 查看图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Request $request, $id){
        $newList = [];
        $info = FuncLostCreditRecord::where([
            ['id', $id],
        ])->first();
        $dataList = json_decode($info->data, true);
        $currentDate = $info->date;
        $legalName = '';
        $legalCard = '';
        foreach ($dataList as &$item) {
            if($item['kind'] == 'legalPerson') {
                $legalName = $item['name'];
                $legalCard = $item['idCard'];
            }
            if($item['kind'] == 'agentPerson') {
                // 代理人
                if($legalName == $item['name'] && $legalCard == $item['idCard']) {
                    continue;
                }
            }
            $jjr = rpa_jjrcredit_sxstates::where([
                ['name', $item['name']],
                ['idCard', $item['idCard']],
                ['type', $item['type']],
                ['quedate', $currentDate],
            ])->first()->toArray();
            $img = $this->handleImg($jjr);
            $newList[] = [
                'name' => $this->typeList[$item['kind']],
                'type' => $item['kind'],
                'list' => $img
            ];
        }
        return view($this->viewPrefix.'show', [
            'info' => $info,
            'list' => $newList
        ]);
    }

    /**
     * 处理照片数组
     * @param $data
     * @return array
     */
    private function handleImg($data)
    {
        $dataList = [];
        foreach ($this->imageTypeList as $key =>  $v) {
            $url = $data[$key.'paths']; // 多个用逗号隔开
            if($url) {
                $urls = explode(',', $url);
                foreach ($urls as $u) {
                    $dataList[] = [
                        'name' => $v,
                        'url' => encrypt($u)
                    ];
                }
            }
        }
        return $dataList;
    }

}