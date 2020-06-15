<?php
/**
 * Created by PhpStorm.
 * User: cyx
 * Date: 2020/4/10
 * Time: 13:21
 */

namespace App\Http\Controllers\api\TimeTasks;

use App\Http\Controllers\api\BaseApiController;
use Illuminate\Http\Request;

/**
 * 服务器相关
 */
class ServerController extends BaseApiController 
{
    /**
     * 检测数据库同步状态
     */
    public function checkMysqlSyncStatus(Request $request)
    {
        $params = [
            'type' => 'server',
            'action' => 'checkMysqlSyncStatus',
            'param' => []
        ];
        $result = $this->getCrmData($params);
        $result['status'] = 200 == $result['status'] ? 200 : 500;
        //api日志
        $this->apiLog(__FUNCTION__,$request,json_encode($result,true),$request->getClientIp());
        return response()->json($result);
    }
}