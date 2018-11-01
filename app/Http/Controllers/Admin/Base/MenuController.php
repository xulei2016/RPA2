<?php

namespace App\Http\Controllers\Admin\Base;

use Illuminate\Http\Request;
use App\Models\Admin\Base\SysMenu;
use App\Http\Controllers\Base\AdminController;

/**
 * MenuController
 * @author lay
 * @since 2018-10-25
 */
class MenuController extends AdminController
{
    /** 
     * Create a new controller instance. 
     * 
     * @return void 
     */ 
    public function __construct() 
    { 
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.base.menu.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuList = SysMenu::where('is_use', 1)
                    ->get()
                    ->toArray();

        // foreach($menuList as $key => $menu){
        //     if(0 != $menu['parent_id']){
        //         array_push($menuList[$menu['parent_id'] - 1]['child'], $menu);
        //         unset($menuList[$key]);
        //     }else{
        //         $menuList[$key]['child'] = [];
        //     }
        // }
        for($i = count($menuList)-1; $i>0; $i--){
            for($j = 0; $j < $i; $j++){
                if($menuList[$j]['parent_id'] > $menuList[$j+1]['parent_id']){
                    $temp = $menuList[$j];
                    $menuList[$j] = $menuList[$j+1];
                    $menuList[$j+1] = $temp;
                    $flag = 1;
                }elseif($menuList[$j]['parent_id'] == $menuList[$j+1]['parent_id']){
                    if($menuList[$j]['order'] > $menuList[$j+1]['order']){
                        $temp = $menuList[$j];
                        $menuList[$j] = $menuList[$j+1];
                        $menuList[$j+1] = $temp;
                        $flag = 1;
                    }
                }
            }
        }
        return view('admin.base.menu.add', ['menuList' => $menuList]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
