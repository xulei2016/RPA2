<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">修改操作</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" id="form" onsubmit="add($(this));return false;">
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}" placeholder="名称" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="guard_name" class="col-sm-2 control-label"><span class="must-tag">*</span>用户组</label>
                    <div class="col-sm-10">
                        <select name="guard_name" id="guard_name" class="form-control">
                            <option value="admin" selected>管理员</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="type" class="col-sm-2 control-label">状态</label>
                    <div class="col-sm-10">
                        <label><input type="radio" class="form-control minimal" name="type" value="1" @if(1 == $info->type) checked @endif>启用</label>
                        <label><input type="radio" class="form-control minimal" name="type" value="0" @if(0 == $info->type) checked @endif>禁用</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述">{{ $info->desc }}</textarea>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {{ method_field('PATCH')}}
                <input type="hidden" name="id" value="{{ $info->id }}">
                <button type="submit" class="btn btn-info pull-right" id="save">提交</button>
                <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续修改</label></div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>
    <script>
        //iCheck for checkbox and radio inputs
        $(document).ready(function(){
            $('#modal input.minimal').iCheck({
                checkboxClass: 'icheckbox_minimal-blue',
                radioClass: 'iradio_minimal-blue',
            });
        });
    
        //添加
        function add(e){
            RPA.ajaxSubmit(e, FormOptions);
        }
        
        var id = "{{ $info->id }}";

        //提交信息的表单配置
        var FormOptions={
            url:'/admin/sys_role/'+id,
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