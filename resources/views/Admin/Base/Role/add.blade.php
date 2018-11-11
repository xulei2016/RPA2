<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">添加操作</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" id="form">
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" placeholder="名称" required>
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
                        <label><input type="radio" class="form-control minimal" name="type" value="1" checked>启用</label>
                        <label><input type="radio" class="form-control minimal" name="type" value="0">禁用</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述"></textarea>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="reset" class="btn btn-warning" id="form-reset">重置</button>
                <button type="button" class="btn btn-info pull-right" id="save">提交</button>
                <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续添加</label></div>
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

        $('#modal #form #save').click(function(){
            add($(this).parents('#form'));
        });
    
        //添加
        function add(e){
            RPA.ajaxSubmit(e, FormOptions);
        }
        
        //提交信息的表单配置
        var FormOptions={
            url:'/admin/sys_role',
            success:function(json, xml){
                if(200 == json.code){
                    toastr.success('操作成功！');
                    $.pjax.reload('#pjax-container');
                    var formContinue = $('#form-continue').is(':checked');
                    !formContinue ? $('#modal').modal('hide') : $('#model #form-reset').click() ;
                }else{
                    toastr.error(json.info);
                }
            },
            error:RPA.errorReponse
        };
    </script>