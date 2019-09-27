<?php


namespace App\Http\Controllers\Admin\Func;


use App\Http\Controllers\base\BaseAdminController;
use Illuminate\Http\Request;

class BzjController extends BaseAdminController
{

    private $table = 'TGSPZBZJ';

    /**
     * 列表页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $this->log(__CLASS__, __FUNCTION__, $request, "查看 保证金标准 页");
        return view('admin/func/Bzj/index');
    }

    /**
     * 数据获取
     * @param Request $request
     * @return array|false|string
     */
    public function pagination(Request $request)
    {
        $jys = $request->get('jys', null); // 交易所
        $hydm = $request->get('hydm', null); // 合约代码
        $table = $request->get('type', 'TGSPZBZJ'); // 表名
        if(!$table) $table = 'TGSPZBZJ';
        $this->table = $table;
        $rows = $request->get('rows', 10); // 每页条数
        $page = $request->get('page', 1); // 页数
        $start = ($page-1)*$rows;
        $end = $rows*$page;
        $by = [];
        if($jys) {
            $by[] = ['JYS', '=', $jys];
        }
        if($hydm) {
            $by[] = ['HYDM', ' like ', "'%$hydm%'"];
        }
        $where = " where 1 = 1 ";
        if(!empty($by)) {
            foreach ($by as $k => $v) {
                $where .= " and ( {$v[0]} {$v[1]} {$v[2]} ) ";
            }
        }
        $sqlCount = "select count(ID) as total from {$table} {$where}";
        $sqlData = "SELECT * FROM (SELECT A.*, rownum r FROM {$table} A {$where} and  rownum <= {$end}) B WHERE r > {$start}";
        $countResult = $this->getData($sqlCount);
        $total = $countResult[0]['TOTAL'];
        if(!$total) {
            return $this->ajax_return(500, '没有找到匹配的记录');
        }
        $totalPage = ceil($total/$rows);
        $list = $this->getData($sqlData);
        $jysList = [
            '1' => '大连交易所',
            '2' => '上海交易所',
            '3' => '中国金融期货交易所',
            '4' => '郑州交易所',
            '5' => '能源交易所',
        ];
        foreach ($list as $k => $v) {
            $list[$k]['JYS'] = $jysList[$v['JYS']];
        }
        $nextPageUrl = $totalPage > $page ? "/admin/rpa_byj/list?page=".($page+1):null;
        $prevPageUrl = $page == 1 ? null : "/admin/rpa_byj/list?page=".($page-1);
        $result = [
            'current_page' => $page,
            'data' => $list,
            'first_page_url' => "/admin/rpa_byj/list?page=1",
            'from' => $start,
            'last_page' => $totalPage,
            'last_page_url' => "/admin/rpa_byj/list?page=$totalPage",
            'next_page_url' => $nextPageUrl,
            'path' => "/admin/rpa_byj/list",
            'per_page' => $rows,
            'prev_page_url' => $prevPageUrl,
            'to' => $end,
            'total' => $total,
        ];
        return json_encode($result);
    }

    /**
     * 获取crm数据
     * @param $sql
     * @return mixed
     */
    public function getData($sql){
        $params = [
            'type' => 'common',
            'action' => 'getEveryBy',
            'param' => [
                'table' => $this->table,
                'by' => $sql
            ]
        ];
        $result = $this->getCrmData($params);
        return $result;
    }
}