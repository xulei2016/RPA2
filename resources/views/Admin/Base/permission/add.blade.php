@component('admin.widgets.addForm')
    @slot('formContent')

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">名称</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" placeholder="请使用英文名称" required>
                </div>
            </div>
            <div class="form-group">
                <label for="desc" class="col-sm-2 control-label">简述</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="desc" id="desc" placeholder="简述" required>
                </div>
            </div>
            <div class="form-group">
                <label for="guard_name" class="col-sm-2 control-label">权限树</label>
                <div class="col-sm-10">
                    <select name="pid" class="form-control pid" id="select2_menu">
                        <option value="">顶级权限</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="guard_name" class="col-sm-2 control-label">所属分组</label>
                <div class="col-sm-10">
                    <select name="guard_name" class="form-control" id="guard_name">
                        <option value="admin" selected>后台管理</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sort" class="col-sm-2 control-label">排序</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" value="5" name="sort" id="sort" placeholder="排序">
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">状态</label>
                <div class="col-sm-10">
                    <label><input type="radio" class="minimal icheck" name="status" value="1" checked>启用</label>
                    <label><input type="radio" class="minimal icheck" name="status" value="0">禁用</label>
                </div>
            </div>
            <input type="hidden" name="table" value="1" id="table">
            
    @endslot
    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/permission/add.js')}}"></script>
    @endslot
@endcomponent