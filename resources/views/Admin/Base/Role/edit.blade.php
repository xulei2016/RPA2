@component('admin.widgets.editForm')
    @slot('formContent')

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
                <label><input type="radio" class="form-control icheck minimal" name="type" value="1" @if(1 == $info->type) checked @endif>启用</label>
                <label><input type="radio" class="form-control icheck minimal" name="type" value="0" @if(0 == $info->type) checked @endif>禁用</label>
            </div>
        </div>
        <div class="form-group">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述">{{ $info->desc }}</textarea>
            </div>
        </div>
        <input type="hidden" name="id" value="{{ $info->id }}">
        
    @endslot
@endcomponent
    <script>

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
                    RPA.form.response();
                }else{
                    toastr.error(json.info);
                }
            },
            error:RPA.errorReponse
        };
    </script>