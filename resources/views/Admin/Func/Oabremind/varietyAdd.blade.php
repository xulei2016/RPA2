@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group row">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>品种名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" placeholder="品种名称">
    </div>
</div>

<div class="form-group row">
    <label for="exfund" class="col-sm-2 control-label"><span class="must-tag">*</span>最低资金</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="exfund" id="exfund" placeholder="最低资金余额(单位:元)">
    </div>
</div>

<div class="form-group row">
    <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>备注</label>
    <div class="col-sm-10">
        <textarea type="text" class="form-control" name="desc" id="desc"></textarea>
    </div>
</div>
@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/func/Oabremind/varietyAdd.js')}}"></script>
@endslot
@endcomponent