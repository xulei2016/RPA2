@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>查询机构</label>
    <div class="col-sm-10">
        <label><input type="radio" name="name" value="SupervisionCFA" checked> 期货业协会</label>
        <label><input type="radio" name="name" value="SupervisionSF"> 证券监督机构</label>
    </div>
</div>
<div class="form-group ">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数设置</label>
    <div class="col-sm-10">
        <div class="target_web">
            @if($jsondata)
                <div class="row">
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="jsonaccount" name="jsonaccount" placeholder="账号" value="{{$jsondata['account']}}">
                    </div>
                    <div class="col-xs-4">
                         <input type="text" class="form-control" id="jsonpwd" name="jsonpwd" placeholder="密码" value="{{$jsondata['pwd']}}">
                     </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="jsondate" name="jsondate" placeholder="指定查询日期" value="{{$jsondata['date']}}">
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="jsonaccount" name="jsonaccount" placeholder="账号">
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="jsonpwd" name="jsonpwd" placeholder="密码">
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" id="jsondate" name="jsondate" placeholder="指定查询日期">
                    </div>
                </div>
            @endif
        </div>
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
<script src="{{URL::asset('/js/admin/rpa/discredit/add_immed.js')}}"></script>
@endslot
@endcomponent