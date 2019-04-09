<?php

namespace App\Models\Base;

use App\model\admin\base\SysError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * base_model
 * @author hsu lay
 * @copyright easyweb
 * @version 1.0 2018.02
 */
class BaseModel extends Model
{
    /**
	 * 添加错误日志
	 * @param string $class 类名
	 * @param string $function 方法名
	 * @param string $info 详细信息
	 * @return boolean
	 */
	public function errorLog($class,$function,$info){
        $admin_info = session('sys_admin');

		$row['class'] = strrchr($class, '\\');
		$row['function'] = $function;
		$row['info'] = env('APP_DEBUG') ? $info : '' ;
		$row['account'] = $admin_info['name'];
		$row['ip'] = $admin_info['lastIp'];
		$row['agent'] = $admin_info['lastAgent'];
		$row['province'] = $admin_info['lastAddress']['country'];
		$row['city'] = $admin_info['lastAddress']['city'];
		$row['add_time'] = date("Y-m-d H:i:s",time());
		if(!is_array($row) || empty($row))return FALSE;
		$result = sysError::create($row);
        return $this->error_return('500',$info,false);
	}
	
	/**
	 * 返回信息
	 * @param string $status 状态码
	 * @param array $data 数据
	 * @return boolean
	 */
	public function error_return($status,$data,$type= true){
		return [
			'code' => $status,
			'info' => $type ? '操作成功!' : 'oops! something went wrong!',
			'data' => $data
		];
	}
}
