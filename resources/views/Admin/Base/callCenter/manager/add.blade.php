@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group">
            <label for="nickname" class="col-sm-2 control-label"><span class="must-tag">*</span>昵称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="nickname" id="nickname" placeholder="昵称">
            </div>
        </div>

        <div class="form-group">
            <label for="work_number" class="col-sm-2 control-label"><span class="must-tag">*</span>工号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="work_number" id="work_number" placeholder="工号">
            </div>
        </div>

        <div class="form-group">
            <label for="label" class="col-sm-2 control-label"><span class="must-tag">*</span>标签</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="label" id="label" placeholder="标签(可以有多个,用英文逗号分开)">
            </div>
        </div>


        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">分组</label>
            <div class="col-sm-10">
                <select name="group_id" class="form-control" id="group_id">
                    @foreach($groups as $group)
                        <option value ="{{ $group['id'] }}">{{ $group['group'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">关联用户</label>
            <div class="col-sm-10">
                <select name="sys_admin_id" class="form-control" id="select2-menu">
                    @foreach($admins as $admin)
                        <option value ="{{ $admin['id'] }}">{{ $admin['realName'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="desc" id="desc" placeholder="描述">
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/callCenter/manager/add.js')}}"></script>
    @endslot
@endcomponent