@component('Admin.widgets.editForm')
    @slot('formContent')
        <div class="form-group row">
            <label for="nickname" class="col-sm-2 control-label"><span class="must-tag">*</span>账号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" placeholder="账号" >
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>密码</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="desc" id="desc" placeholder="密码">
            </div>
        </div>

        <input type="hidden" name="id" id="id" value="{{$apply->id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/plugin/apply/edit.js')}}"></script>
    @endslot
@endcomponent
