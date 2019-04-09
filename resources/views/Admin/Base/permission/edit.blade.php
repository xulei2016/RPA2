@component('admin.widgets.editForm')
@slot('formContent')
                <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">名称</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}" placeholder="请使用英文名称" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="desc" class="col-sm-2 control-label">简述</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="desc" id="desc" value="{{ $info->desc }}" placeholder="简述" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="guard_name" class="col-sm-2 control-label">权限树</label>
                    <div class="col-sm-10">
                        <select name="pid" class="form-control pid" id="select2_menu">
                            <option value="{{ $info->pid }}">父级菜单</option>
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
                        <input type="text" class="form-control" name="sort" value="{{ $info->sort }}" id="sort" placeholder="排序">
                    </div>
                </div>
                <div class="form-group">
                    <label for="status" class="col-sm-2 control-label">状态</label>
                    <div class="col-sm-10">
                        <div class="switch">
                            <input type="checkbox" name="status" id="status" value="1" @if(1 == $info->status) checked @endif />
                        </div>
                    </div>
                </div>
                <input type="hidden" name="table" value="{{ $info->table }}" id="table">
                <input type="text" class="hidden" name="id" id="id" value="{{ $info->id }}">
@endslot
@slot('formScript')
<script src="{{URL::asset('/js/admin/base/permission/edit.js')}}"></script>
@endslot
@endcomponent