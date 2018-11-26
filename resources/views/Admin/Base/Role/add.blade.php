@component('admin.widgets.addForm')
    @slot('formContent')

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
                        <label><input type="radio" class="form-control icheck minimal" name="type" value="1" checked>启用</label>
                        <label><input type="radio" class="form-control icheck minimal" name="type" value="0">禁用</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc" class="col-sm-2 control-label">描述</label>
                    <div class="col-sm-10">
                        <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述"></textarea>
                    </div>
                </div>

    @endslot
@endcomponent
    <script>
        //添加
        function add(e){
            RPA.ajaxSubmit(e, FormOptions);
        }
        
        //提交信息的表单配置
        var FormOptions={
            url:'/admin/sys_role',
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