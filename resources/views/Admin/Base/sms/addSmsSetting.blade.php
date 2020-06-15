@component('admin.widgets.addForm')
    @slot('formContent')
        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>网关名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="unique_name" class="col-sm-2 control-label"><span class="must-tag">*</span>代号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="unique_name" id="unique_name" placeholder="唯一代号">
            </div>
        </div>
        <div class="form-group row">
            <label for="status" class="col-sm-2 control-label"><span class="must-tag">*</span>状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="form-control switch" name="status" value="1" id="status" placeholder="状态">
            </div>
        </div>
        <div class="form-group row">
            <label for="managerAddress" class="col-sm-2 control-label"><span class="must-tag">*</span>后台管理地址</label>
            <div class="col-sm-10">
                <input type="url" class="form-control" name="managerAddress" id="managerAddress" placeholder="后台管理地址">
            </div>
        </div>
        <div class="form-group row">
            <label for="uri" class="col-sm-2 control-label"><span class="must-tag">*</span>配置</label>
            <div class="col-sm-10">
                <div class="jsoneditor">
                    <div id="setting" style="min-height: 400px;"></div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>备注</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" style="min-height: 200px;"></textarea>
            </div>
        </div>

    @endslot
    @slot('formScript')
        <link rel="stylesheet" href="{{URL::asset('/include/jsoneditor/dist/jsoneditor.min.css')}}">
        <script src="{{URL::asset('/include/jsoneditor/dist/jsoneditor.min.js')}}"></script>
        <script src="{{URL::asset('/js/admin/base/sms/addSmsSetting.js')}}"></script>
    @endslot
@endcomponent