@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="OABReminding" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
    <div class="col-sm-10">
        <textarea type="text" class="form-control" name="description" id="description" placeholder="任务描述" required></textarea>
    </div>
</div>
<input type="hidden" class="form-control" id="jsondata" name="jsondata">

@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/rpa/Oabreminding/add_immed.js')}}"></script>
@endslot
@endcomponent