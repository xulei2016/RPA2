@component('admin.widgets.editForm')
    @slot('formContent')
        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}" placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="select2-menu" class="col-sm-2 control-label"><span class="must-tag">*</span>角色选择</label>
            <div class="col-sm-10">
                <select name="roleLists[]" id="select2-menu" class="form-control parent_id select2" multiple>
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
        <div class="form-group row">
            <label for="realName" class="col-sm-2 control-label"><span class="must-tag">*</span>真实姓名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="realName" id="realName" value="{{ $info->realName }}" placeholder="真实姓名">
            </div>
        </div>
        <div class="form-group row">
            <label for="groupID" class="col-sm-2 control-label"><span class="must-tag">*</span>选择分组</label>
            <div class="col-sm-10">
                <select class="form-control" name="groupID" id="groupID">
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
        <div class="form-group row">
            <label for="password" class="col-sm-2 control-label">密码</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" id="password" placeholder="密码" autocomplete>
            </div>
        </div>
        <div class="form-group row">
            <label for="rePWD" class="col-sm-2 control-label">确认密码</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="rePWD" id="rePWD" placeholder="确认密码" autocomplete>
            </div>
        </div>
        <div class="form-group row">
            <label for="sex" class="col-sm-2 control-label">性别</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="sex" id="sex" value="1"  @if(1 == $info->sex) checked @endif />
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-2 control-label">联系电话</label>
            <div class="col-sm-10">
                <input type="phone" class="form-control" name="phone" id="phone" value="{{ $info->phone }}" placeholder="联系电话">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" value="{{ $info->email }}" placeholder="Email">
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 control-label">状态</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="type" id="type" value="1"  @if(1 == $info->type) checked @endif />
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述">{{ $info->desc }}</textarea>
            </div>
        </div>
        <input type="hidden" name="id" id='id' value="{{ $info->id }}">
        
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/Admin/admin/edit.js')}}"></script>
    @endslot
@endcomponent