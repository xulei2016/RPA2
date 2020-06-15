@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="nickname" class="col-sm-12 control-label"><span class="must-tag">*</span>插件名称</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="插件名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="name_en" class="col-sm-12 control-label"><span class="must-tag">*</span>英文名称(提交后无法修改)</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name_en" id="name_en" placeholder="英文名称">
            </div>
        </div>


        <div class="form-group row">
            <label for="desc" class="col-sm-12 control-label">描述</label>
            <div class="col-sm-12">
                <textarea  id="editor_desc" class="form-control" name="desc" id="desc" placeholder="描述">
</textarea>
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/plugin/plugin/add.js')}}"></script>
    @endslot
@endcomponent
