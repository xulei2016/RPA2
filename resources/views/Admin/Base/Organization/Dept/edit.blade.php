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
            <label for="manager_id" class="col-sm-2 control-label"><span class="must-tag">*</span>负责人</label>
            <div class="col-sm-10">
                <select name="manager_id" id="manager_id" class="form-control">
                    <option value="">未选择</option>
                    @foreach($admins as $admin)
                        @if($admin->id == $info->manager_id)
                            <option value="{{$admin->id}}" selected>{{$admin->realName}}</option>
                        @else
                            <option value="{{$admin->id}}">{{$admin->realName}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="leader_id" class="col-sm-2 control-label"><span class="must-tag">*</span>分管领导</label>
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
        <input type="hidden" name="id" id='id' value="{{ $info->id }}">
        <input type="hidden" name="pid" id='pid' value="{{ $info->pid }}">

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('js/admin/base/organization/dept/edit.js')}}"></script>
    @endslot
@endcomponent