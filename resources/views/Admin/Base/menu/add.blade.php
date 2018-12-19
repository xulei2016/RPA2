@component('admin.widgets.addForm')
@slot('formContent')
    <div class="form-group">
        <label for="parent_id" class="col-sm-2 control-label">父级菜单</label>
        <div class="col-sm-10">
            <select name="parent_id" id="select2-menu" class="form-control parent_id" id="select2-menu">
                <option value="">父级菜单</option>
                @foreach($menuList as $menus)
                @if(empty($menus['child']))
                <option value ="{{ $menus['id'] }}">{{ $menus['title'] }}</option>
                @else
                <option value ="{{ $menus['id'] }}">{{ $menus['title'] }}
                    @foreach($menus['child'] as $menu)
                        <option value="{{ $menu['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $menu['title'] }}</option>
                    @endforeach
                </option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class="col-sm-2 control-label"><span class="must-tag">*</span>名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="title" id="title" placeholder="名称">
        </div>
    </div>
    <div class="form-group">
        <label for="unique_name" class="col-sm-2 control-label"><span class="must-tag">*</span>权限键值</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="unique_name" id="unique_name" placeholder="unique_name">
        </div>
    </div>
    <div class="form-group">
        <label for="uri" class="col-sm-2 control-label"><span class="must-tag">*</span>路径</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="uri" id="uri" placeholder="输入路径">
        </div>
    </div>
    <div class="form-group">
        <label for="icon" class="col-sm-2 control-label"><span class="must-tag">*</span>图标</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="icon" id="icon" placeholder="图标">
        </div>
    </div>
    <div class="form-group">
        <label for="order" class="col-sm-2 control-label"><span class="must-tag">*</span>排序</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="5" name="order" id="order" placeholder="排序">
        </div>
    </div>

@endslot
@slot('formScript')
    <script src="{{URL::asset('/js/admin/base/menu/add.js')}}"></script>
@endslot
@endcomponent