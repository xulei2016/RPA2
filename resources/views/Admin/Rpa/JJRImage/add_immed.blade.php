@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group row">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="Lcztcx" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group row ">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数设置</label>
    <div class="col-sm-10">
        <div class="target_web">
            @if($jsondata)
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control" id="jsondate" name="jsondate" placeholder="指定查询日期(可选)" value="{{$jsondata['date']}}">
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-xs-12">
                        <input type="text" class="form-control" id="jsondate" name="jsondate" placeholder="指定查询日期(可选)">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
    <div class="col-sm-10">
        <textarea type="text" class="form-control" name="description" id="description" placeholder="任务描述" required></textarea>
    </div>
</div>
<input type="hidden" class="form-control" id="jsondata" name="jsondata">

@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/rpa/JJRImage/add_immed.js')}}"></script>
@endslot
@endcomponent