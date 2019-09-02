@component('admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="guard_name" class="col-sm-2 control-label"><span class="must-tag">*</span>用户组</label>
            <div class="col-sm-10">
                <select name="guard_name" id="guard_name" class="form-control">
                    <option value="admin" selected>管理员</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="type" class="col-sm-2 control-label">状态</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="type" id="type" value="1" checked />
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" placeholder="描述"></textarea>
            </div>
        </div>

    @endslot
    @slot('formScript')
    <script src="{{URL::asset('/js/admin/base/role/add.js')}}"></script>
    @endslot
@endcomponent