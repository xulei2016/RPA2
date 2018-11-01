<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">添加菜单</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal">
        <div class="box-body">
            <div class="form-group">
                <label for="parent_id" class="col-sm-2 control-label">父级菜单</label>
                <div class="col-sm-10">
                    <select name="" id="" class="form-control parent_id" id="select2-menu">
                        <option value="">父级菜单</option>
                        @foreach($menuList as $menus)
                        @if(empty($menus['child']))
                        <option value ="{{ $menus['id'] }}">{{ $menus['title'] }}</option>
                        @else
                        <option value ="{{ $menus['id'] }}">{{ $menus['title'] }}
                            @foreach($menus['child'] as $menu)
                                <option value="{{ $menu['id'] }}">{{ $menu['title'] }}</option>
                            @endforeach
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="名称">
                </div>
            </div>
            <div class="form-group">
                <label for="uri" class="col-sm-2 control-label">地址</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="uri" id="uri" placeholder="地址">
                </div>
            </div>
            <div class="form-group">
                <label for="icon" class="col-sm-2 control-label">图标</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="icon" id="icon" placeholder="图标">
                </div>
            </div>
            <div class="form-group">
                <label for="role" class="col-sm-2 control-label">角色</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="role" id="role" placeholder="角色">
                </div>
            </div>
            <div class="form-group">
                <label for="permission" class="col-sm-2 control-label">权限</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="permission" id="permission" placeholder="权限">
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="reset" class="btn btn-warning">重置</button>
            <button type="submit" class="btn btn-info pull-right">提交</button>
            <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal">继续添加</label></div>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<script>
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    $("#select2-menu").select2({
        "allowClear":true,
        "placeholder":"父级菜单",
    });
</script>