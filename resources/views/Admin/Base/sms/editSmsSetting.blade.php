@component('admin.widgets.editForm')
    @slot('formContent')
        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>网关名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{ $Setting->name }}" id="name"
                       placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="unique_name" class="col-sm-2 control-label"><span class="must-tag">*</span>代号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="unique_name" value="{{ $Setting->unique_name }}" id="unique_name" placeholder="唯一代号(大写)">
            </div>
        </div>
        <div class="form-group row">
            <label for="status" class="col-sm-2 control-label"><span class="must-tag">*</span>状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="switch form-control" name="status" value="1" @if($Setting->status) checked
                       @endif id="status" placeholder="状态">
            </div>
        </div>
        <div class="form-group row">
            <label for="managerAddress" class="col-sm-2 control-label"><span class="must-tag">*</span>后台管理地址</label>
            <div class="col-sm-10">
                <input type="url" class="form-control" name="managerAddress" value="{{ $Setting->managerAddress }}"
                       id="managerAddress" placeholder="后台管理地址">
            </div>
        </div>
        <div class="form-group row">
            <label for="setting" class="col-sm-2 control-label"><span class="must-tag">*</span>配置</label>
            <div class="col-sm-10">
                <div class="jsoneditor">
                    <div id="setting" style="min-height: 400px;"></div>
                </div>
                <input type="hidden" class="form-control" name="setting" value="{{ $Setting->setting }}">
            </div>
        </div>
        <div class="form-group row">
            <label for="return_code" class="col-sm-2 control-label"><span class="must-tag">*</span>返回码</label>
            <div class="col-sm-10">
                <div class="jsoneditor">
                    <div id="return_code" style="min-height: 400px;"></div>
                </div>
                <input type="hidden" class="form-control" name="return_code" value="{{ $Setting->return_code }}">
            </div>
        </div>
        <div class="form-group row hidden">
            <div class="col-sm-10">
                <input type="text" class="form-control" name="id" value="{{ $Setting->id }}" id="id" placeholder="id">
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>备注</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" style="min-height: 200px;">{{ $Setting->desc }}</textarea>
            </div>
        </div>

    @endslot
    @slot('formScript')
        <link rel="stylesheet" href="{{URL::asset('/include/jsoneditor/dist/jsoneditor.min.css')}}">
        <script src="{{URL::asset('/include/jsoneditor/dist/jsoneditor.min.js')}}"></script>
        <script src="{{URL::asset('/js/admin/base/sms/editSmsSetting.js')}}"></script>
    @endslot
@endcomponent