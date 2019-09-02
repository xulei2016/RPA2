@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group row">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="SettlementFee" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group row ">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数设置</label>
    <div class="col-sm-10">
        <div class="target_web">
            <div class="row">
                @if($jsondata)
                    <div class="col-xs-12">
                        <label><input type="radio" name="jsondate" value="8200" @if($jsondata['model_kind'] == '8200')checked @endif> 8200</label>
                        <label><input type="radio" name="jsondate" value="8300" @if($jsondata['model_kind'] == '8300')checked @endif> 8300</label>
                    </div>
                @else
                    <div class="col-xs-12">
                        <label><input type="radio" name="jsondate" value="8200" checked> 8200</label>
                        <label><input type="radio" name="jsondate" value="8300"> 8300</label>
                    </div>
                @endif
            </div>
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
<script src="{{URL::asset('/js/admin/rpa/SettlementFee/add_immed.js')}}"></script>
@endslot
@endcomponent