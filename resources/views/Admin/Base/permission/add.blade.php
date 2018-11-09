<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">添加操作</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form" onsubmit="add($(this));return false;">
        <div class="box-body">
            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="请使用英文名称" required>
                </div>
            </div>
            <div class="form-group">
                <label for="desc" class="col-sm-2 control-label">简述</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="desc" id="desc" placeholder="简述" required>
                </div>
            </div>
            <div class="form-group">
                <label for="guard_name" class="col-sm-2 control-label">权限树</label>
                <div class="col-sm-10">
                    <select name="pid" id="select2-menu" class="form-control pid" id="select2-menu">
                        <option value="">顶级权限</option>
                        {{-- @foreach($menuList as $menus)
                        @if(empty($menus['child']))
                        <option value ="{{ $menus['id'] }}">{{ $menus['title'] }}</option>
                        @else
                        <option value ="{{ $menus['id'] }}">{{ $menus['title'] }}
                            @foreach($menus['child'] as $menu)
                                <option value="{{ $menu['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $menu['title'] }}</option>
                            @endforeach
                        </option>
                        @endif
                        @endforeach --}}
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="guard_name" class="col-sm-2 control-label">所属分组</label>
                <div class="col-sm-10">
                    <select name="guard_name" class="form-control" id="guard_name">
                        <option value="admin" selected>后台管理</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-sm-2 control-label">排序</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="5" name="sort" id="sort" placeholder="排序">
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-10">
                    <label><input type="radio" class="minimal" name="status" value="1" checked>启用</label>
                    <label><input type="radio" class="minimal" name="status" value="0">禁用</label>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <input type="hidden" name="table" value="1" id="table">
            <button type="reset" class="btn btn-warning" id="form-reset">重置</button>
            <button type="submit" class="btn btn-info pull-right" id="save">提交</button>
            <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续添加</label></div>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<script>
    //iCheck for checkbox and radio inputs
    $('input.minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });

    $("#select2-menu").select2({
        "allowClear":true,
        "placeholder":"父级菜单",
    });

    $.post('/admin/permission/getTree', {}, function(json){
        console.log(json);
        if(200 == json.code){
            html = initTree(json.data);
            $('#select2-menu').append(html);
        }else{
            Swal(json.info, '', 'error');
        }
    });

    function initTree(data){
        var num = data.length;
        let html = '';
        let space = '&nbsp;&nbsp;';
        for(let i = 0;i < num; i++){
            let json = data[i];
            html += "<option value ="+json.id+">"+ moreString(json['table']) + json.name +"</option>"
            if(json.hasOwnProperty('child')){
                html += initTree(json.child);
            }
        }
        return html;
    }

    function moreString(n){
        let html = '&nbsp;';
        let i = 0;
        while(i < n){
            i++;
            html += html;
        }
        return html;
    }

    //添加
    function add(e){
        let table = $('#modal #select2-menu:selected').attr('table');
        $('#modal #table').val(table+1);
        RPA.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/permission',
        success:successResponse,
        error:RPA.errorReponse
    };

    var successResponse = function(json, xml){
        if(200 == json.code){
            toastr.success('操作成功！');
            var formContinue = $('#form-continue').is(':checked');
            !formContinue ? $('#modal').modal('hide') : $('#form-reset').click();
            $.pjax.reload('#pjax-container');
        }else{
            toastr.error(json.info);
        }
    }
</script>