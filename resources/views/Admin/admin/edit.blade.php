@component('admin.widgets.editForm')
    @slot('formContent')
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}" placeholder="名称" required>
            </div>
        </div>
        <div class="form-group">
            <label for="select2-menu" class="col-sm-2 control-label"><span class="must-tag">*</span>角色选择</label>
            <div class="col-sm-10">
                <select name="roleLists[]" id="select2-menu" class="form-control parent_id select2" multiple required>
                    @foreach($roles as $role)
                        @if(in_array($role['name'], $info->roleLists))
                            <option value ="{{ $role['name'] }}" selected>{{ $role['name'] }}</option>
                        @else
                            <option value ="{{ $role['name'] }}">{{ $role['name'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="realName" class="col-sm-2 control-label"><span class="must-tag">*</span>真实姓名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="realName" id="realName" value="{{ $info->realName }}" placeholder="真实姓名" required>
            </div>
        </div>
        <div class="form-group">
            <label for="groupID" class="col-sm-2 control-label"><span class="must-tag">*</span>选择分组</label>
            <div class="col-sm-10">
                <select class="form-control" name="groupID" id="groupID" required>
                    <option value="">请选择</option>
                    @foreach($groupList as $group)
                        @if($info->groupID == $group->id)
                        <option value="{{ $group->id }}" selected>{{ $group->group }}</option>
                        @else
                        <option value="{{ $group->id }}">{{ $group->group }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label"><span class="must-tag">*</span>密码</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" id="password" placeholder="密码">
            </div>
        </div>
        <div class="form-group">
            <label for="rePWD" class="col-sm-2 control-label"><span class="must-tag">*</span>确认密码</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="rePWD" id="rePWD" placeholder="确认密码">
            </div>
        </div>
        <div class="form-group">
            <label for="sex" class="col-sm-2 control-label">性别</label>
            <div class="col-sm-10">
                <label><input type="radio" class="form-control icheck minimal" name="sex" value="1" @if(1 == $info->sex) checked @endif>男</label>
                <label><input type="radio" class="form-control icheck minimal" name="sex" value="0" @if(0 == $info->sex) checked @endif>女</label>
            </div>
        </div>
        <div class="form-group">
            <label for="phone" class="col-sm-2 control-label">联系电话</label>
            <div class="col-sm-10">
                <input type="phone" class="form-control" name="phone" id="phone" value="{{ $info->phone }}" placeholder="联系电话">
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" value="{{ $info->email }}" placeholder="Email">
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="col-sm-2 control-label">状态</label>
            <div class="col-sm-10">
                <label><input type="radio" class="form-control icheck minimal" name="type" value="1" @if(1 == $info->sex) checked @endif>启用</label>
                <label><input type="radio" class="form-control icheck minimal" name="type" value="0" @if(0 == $info->sex) checked @endif>禁用</label>
            </div>
        </div>
        <div class="form-group">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述">{{ $info->desc }}</textarea>
            </div>
        </div>
        {{ method_field('PATCH')}}
        <input type="hidden" name="id" value="{{ $info->id }}">
        
    @endslot
@endcomponent
    <script>
        $("#select2-menu").select2({
            "allowClear":true,
            "placeholder":"角色选择",
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
        
        var id = "{{ $info->id }}";
        
        //提交信息的表单配置
        var FormOptions={
            url:'/admin/sys_admin/'+id,
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