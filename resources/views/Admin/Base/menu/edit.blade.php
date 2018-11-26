@component('admin.widgets.editForm')
@slot('formContent')
            <div class="form-group">
                <label for="parent_id" class="col-sm-2 control-label">父级菜单</label>
                <div class="col-sm-10">
                    <select name="parent_id" id="select2-menu" class="form-control parent_id" id="select2-menu">
                        <option value="">父级菜单</option>
                        @foreach($menuList as $menus)
                            @if(empty($menus['child']))
                                <option value ="{{ $menus['id'] }}" @if($menus['id'] == $menuInfo->parent_id) selected @endif>{{ $menus['title'] }}</option>
                            @else
                                <option value ="{{ $menus['id'] }}" @if($menus['id'] == $menuInfo->parent_id) selected @endif>{{ $menus['title'] }}</option>
                                @foreach($menus['child'] as $menu)
                                    <option value="{{ $menu['id'] }}" disabled>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $menu['title'] }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="title" id="title" value="{{ $menuInfo->title }}" placeholder="名称" required>
                </div>
            </div>
            <div class="form-group">
                <label for="unique_name" class="col-sm-2 control-label">权限键值</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="unique_name" id="unique_name" value="{{ $menuInfo->unique_name }}" placeholder="unique_name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="uri" class="col-sm-2 control-label">路径</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="uri" id="uri" value="{{ $menuInfo->uri }}" placeholder="输入路径" required>
                </div>
            </div>
            <div class="form-group">
                <label for="icon" class="col-sm-2 control-label">图标</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="icon" id="icon" value="{{ $menuInfo->icon }}" placeholder="图标">
                </div>
            </div>
            <div class="form-group">
                <label for="order" class="col-sm-2 control-label">排序</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="order" id="order" value="{{ $menuInfo->order }}" placeholder="排序">
                </div>
            </div>
            <input type="text" class="hidden" name="id" id="id" value="{{ $menuInfo->id }}">
@endslot
@endcomponent
<script>
    $("#select2-menu").select2({
        "allowClear":true,
        "placeholder":"父级菜单",
    });

    //添加
    function add(e){
        RPA.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_menu/edit',
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };
</script>