<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">添加管理员</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form class="form-horizontal" id="form" onsubmit="add($(this));return false;">
            <div class="box-body">
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" placeholder="名称" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="realName" class="col-sm-2 control-label"><span class="must-tag">*</span>真实姓名</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="realName" id="realName" placeholder="真实姓名" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="col-sm-2 control-label"><span class="must-tag">*</span>密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="password" id="password" placeholder="密码" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="rePWD" class="col-sm-2 control-label"><span class="must-tag">*</span>确认密码</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="rePWD" id="rePWD" placeholder="确认密码" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sex" class="col-sm-2 control-label">性别</label>
                    <div class="col-sm-10">
                        <label><input type="radio" class="form-control minimal" name="sex" value="1" checked>男</label>
                        <label><input type="radio" class="form-control minimal" name="sex" value="0">女</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-2 control-label">联系电话</label>
                    <div class="col-sm-10">
                        <input type="phone" class="form-control" name="phone" id="phone" placeholder="联系电话">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" sid="email" placeholder="Email">
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
                <button type="submit" class="btn btn-info pull-right" id="save">提交</button>
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
    
        //添加
        function add(e){
            //密码一致性判断
            var pwd = $('#modal input#password').val();
            var repwd = $('#modal input#rePWD').val();
            if(pwd !== repwd){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: '两次密码输入不一致',
                });
                return false;
            }
            RPA.ajaxSubmit(e, FormOptions);
        }
        
        //提交信息的表单配置
        var FormOptions={
            url:'/admin/admin',
            success:successResponse,
            error:RPA.errorReponse
        };
    
        var successResponse = function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
                $.pjax.reload('#pjax-container');
                var formContinue = $('#form-continue').is(':checked');
                !formContinue ? $('#modal').modal('hide') : '' ;
            }else{
                toastr.error(json.info);
            }
        }
    </script>