<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">修改菜单</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" id="form">
        <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}" placeholder="请使用英文名称" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc" class="col-sm-2 control-label">简述</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="desc" id="desc" value="{{ $info->desc }}" placeholder="简述" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="guard_name" class="col-sm-2 control-label">权限树</label>
                    <div class="col-sm-10">
                        <select name="pid" id="select2-menu" class="form-control pid" id="select2-menu">
                            <option value="">顶级权限</option>
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
                        <input type="text" class="form-control" name="sort" value="{{ $info->sort }}" id="sort" placeholder="排序">
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">状态</label>
                    <div class="col-sm-10">
                        <label><input type="radio" class="minimal" name="status" value="1" @if(1 == $info->status) checked @endif>启用</label>
                        <label><input type="radio" class="minimal" name="status" value="0" @if(0 == $info->status) checked @endif>禁用</label>
                    </div>
                </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            {{ method_field('PATCH')}}
            <input type="text" class="hidden" name="id" id="id" value="{{ $info->id }}">
            <button type="button" class="btn btn-info pull-right" id="save" onclick="add($(this))">提交</button>
            <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续编辑</label></div>
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
    
    $.post('/admin/sys_permission/getTree', {}, function(json){
        if(200 == json.code){
            let pid = "{{ $info->pid }}";
            let id = "{{ $info->id }}";
            html = initTree(json.data, pid, id);
            $('#select2-menu').append(html);
        }else{
            Swal(json.info, '', 'error');
        }
    });

    function initTree(data, pid, id){
        var num = data.length;
        let html = '';
        for(let i = 0;i < num; i++){
            let json = data[i];
            let selected = (json.id == id) ? 'disabled' : ((json.id == pid) ? 'selected' : '' );
            html += "<option value ="+json.id+" table="+json.table+" "+selected+">"+ moreString(json['table']) + json.desc +"</option>"
            if(json.hasOwnProperty('child')){
                html += initTree(json.child, pid, id);
            }
        }
        return html;
    }

    function moreString(n){
        let html = '&nbsp;&nbsp;';
        let i = 0;
        while(i < n){
            i++;
            html += html;
        }
        return html;
    }

    //添加
    function add(e){
        let table = $('#modal #select2-menu option:selected').attr('table');
        $('#modal #table').val(table);
        RPA.ajaxSubmit(e.parents('#form'), FormOptions);
    }
    
    var id = "{{ $info->id }}";

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_permission/'+id,
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
                $.pjax.reload('#pjax-container');
                var formContinue = $('#form-continue').is(':checked');
                !formContinue ? $('#modal').modal('hide') : '' ;
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };

</script>