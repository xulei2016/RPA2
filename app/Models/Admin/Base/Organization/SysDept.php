<?php


namespace App\Models\Admin\Base\Organization;

use App\Models\Admin\Admin\SysAdmin;
use Illuminate\Database\Eloquent\Model;

/**
 * 组织架构-部门
 * Class SysDepartment
 * @package App\Models\Admin\Base\Organization
 */
class SysDept extends Model
{
    protected $table = "sys_depts";

    protected $guarded = [];

    /**
     * recursion function
     *
     * 构造部门
     * @param [array] $depts
     * @param string $html
     * @param integer $pid
     * @param integer $level
     * @return void
     */
    public static function recursion($depts,$html='├──',$pid=0,$level=0)
    {
        $data=[];
        foreach($depts as $k=>$v){
            if($v['pid']==$pid){
                $v['html']=str_repeat($html, $level);
                $v['rank']=$level+1;
                $v['post_ids'] = json_decode($v['post_ids'], true);
                $data[]=$v;
                unset($depts[$k]);
                $data=array_merge($data,self::recursion($depts,$html,$v['id'],$level+1));
            }
        }
        return $data;
    }

    //部门岗位中间表
    public function dept_post_relation()
    {
        return $this->hasMany('App\Models\Admin\Base\Organization\SysDeptPostRelation', 'dept_id');
    }

    //部门岗位
    public function dept_post()
    {
        return $this->hasManyThrough(
            'App\Models\Admin\Base\Organization\SysDeptPost',
            'App\Models\Admin\Base\Organization\SysDeptPostRelation',
            'dept_id',
            'id',
            'id'
        );
    }

    /**
     * 获取所有菜单
     * @return array
     */
    public static function getMenus(){
        $menus = self::orderBy('order', 'asc')->get();
        foreach ($menus as $v) {
            $item = [
                'id' => $v->id,
                'pid' => $v->pid,
                'name' => $v->name,
                'type' => 'node'
            ];
            if(!$v->pid) {
                $item['open'] = true;
            }
            $list[] = $item;
        }
        return $list;
    }

    /**
     * 获取菜单+员工
     */
    public static function getAdmins(){
        $list = self::getMenus();
        $admins = SysAdmin::where('dept_id', '!=', 0)->get();
        foreach ($admins as $v) {
            $list[] = [
                'id' => 'admin_'.$v->id,
                'pid' => $v->dept_id,
                'type' => 'person',
                'name' => $v->realName
            ];
        }
        return $list;
    }
}