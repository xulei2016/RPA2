<?php


namespace App\Models\Admin\Base\Organization;

use Illuminate\Database\Eloquent\Model;

/**
 * 组织架构-部门
 * Class SysDept
 * @package App\Models\Admin\Base\Organization
 */
class SysDept extends Model
{
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
            if($v['parent_id']==$pid){
                $v['html']=str_repeat($html, $level);
                $v['rank']=$level+1;
                $data[]=$v;
                unset($depts[$k]);
                $data=array_merge($data,self::recursion($depts,$html,$v['id'],$level+1));
            }
        }
        return $data;
    }

    //中间表
    function relation()
    {
        return $this->hasMany('App\Models\Admin\Base\Organization\SysDeptRelation', 'dept_id');
    }
}