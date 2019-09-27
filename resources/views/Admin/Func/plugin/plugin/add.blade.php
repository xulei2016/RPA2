@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="nickname" class="col-sm-2 control-label"><span class="must-tag">*</span>插件名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" placeholder="插件名称">
            </div>
        </div>


        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="desc" id="desc" placeholder="描述">
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/plugin/plugin/add.js')}}"></script>
    @endslot
@endcomponent
