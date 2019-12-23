@component('admin.widgets.editForm')
    @slot('formContent')
        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}"
                       placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="select2-menu" class="col-sm-2 control-label"><span class="must-tag">*</span>角色选择</label>
            <div class="col-sm-10">
                <select name="roleLists[]" id="select2-menu" class="form-control parent_id select2" multiple>
                    @foreach($roles as $role)
                        @if(in_array($role['name'], $info->roleLists))
                            <option value="{{ $role['name'] }}" selected>{{ $role['desc'] }}</option>
                        @else
                            <option value="{{ $role['name'] }}">{{ $role['desc'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="realName" class="col-sm-2 control-label"><span class="must-tag">*</span>真实姓名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="realName" id="realName" value="{{ $info->realName }}"
                       placeholder="真实姓名">
            </div>
        </div>

        {{--    department start    --}}
        <div class="form-group row">
            <label for="dept_id" class="col-sm-2 control-label"><span class="must-tag">*</span>部门</label>
            <div class="col-sm-10">
                <select class="form-control select2" name="dept_id" id="dept_id">
                    @if($department)
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @else
                        <option value="">未选择</option>
                    @endif
                    @foreach($departmentList as $d)
                        <option value="{{ $d['id'] }}">{{str_repeat("--", $d['level'])}}{{ $d['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{--    department end    --}}
        {{--    post start    --}}
        <div class="form-group row">
            <label for="posts" class="col-sm-2 control-label"><span class="must-tag">*</span>岗位</label>
            <div class="col-sm-10">
                <select name="posts[]" id="posts" class="form-control select2" multiple autocomplete="off">
                    @foreach($deptPostRelationList as $v)
                        @if(in_array($v->id, $info->posts))
                            <option selected value="{{$v->id}}">{{$v->fullname}}</option>
                        @else
                            <option value="{{$v->id}}">{{$v->fullname}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        {{--    post end    --}}
        {{--    functional start  --}}
        <div class="form-group row">
            <label for="func_id" class="col-sm-2 control-label"><span class="must-tag">*</span>职务</label>
            <div class="col-sm-10">
                <select class="form-control" name="func_id" id="func_id">
                    @foreach($functionalList as $v)
                        @if($info->func_id == $v->id)
                            <option selected value="{{ $v->id }}">{{ $v->name }}</option>
                        @else
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        {{--    functional end    --}}
        {{--    leader_id start    --}}
        <div class="form-group row">
            <label for="leader_id" class="col-sm-2 control-label"> 直接上级</label>
            <div class="col-sm-9">
                <select name="leader_id" id="leader_id" class="form-control">
                    @if($info->leader_id)
                        <option value="{{$leader->id}}">{{$leader->realName}}</option>
                    @else
                        <option value="">未选择</option>
                    @endif
                </select>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-primary searchUser"><i class="fa fa-search"></i></a>
            </div>
        </div>
    {{--    leader_id    --}}
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
                <input type="text" class="form-control" name="password" id="password" placeholder="密码" onfocus="this.type='password'"
                       autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label for="rePWD" class="col-sm-2 control-label">确认密码</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="rePWD" id="rePWD" placeholder="确认密码" onfocus="this.type='password'"
                       autocomplete="off">
            </div>
        </div>
        <div class="form-group row">
            <label for="sex" class="col-sm-2 control-label">性别</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="sex" id="sex" value="1" @if(1 == $info->sex) checked @endif />
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone" class="col-sm-2 control-label">联系电话</label>
            <div class="col-sm-10">
                <input type="phone" class="form-control" name="phone" id="phone" value="{{ $info->phone }}"
                       placeholder="联系电话">
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" value="{{ $info->email }}"
                       placeholder="Email">
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 control-label">状态</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="type" id="type" value="1" @if(1 == $info->type) checked @endif />
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc"
                          placeholder="描述">{{ $info->desc }}</textarea>
            </div>
        </div>
        <input type="hidden" name="id" id='id' value="{{ $info->id }}">

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/Admin/admin/edit.js')}}"></script>
    @endslot
@endcomponent