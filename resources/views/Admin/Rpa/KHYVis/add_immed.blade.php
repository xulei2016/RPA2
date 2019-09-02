@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group row">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="CustomerReview" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group row ">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数设置</label>
    <div class="col-sm-10">
            @if($jsondata)
                @foreach($admin as $v)
                    <label><input type="checkbox" class="select-single" name="jsondata[]" value="{{$v->realName}}" @if(in_Array($v->realName,$jsondata))checked @endif>{{$v->realName}}</label>
                @endforeach
            @else
                @foreach($admin as $v)
                    <label><input type="checkbox" class="select-single" name="jsondata[]" value="{{$v->realName}}">{{$v->realName}}</label>
                @endforeach
            @endif
    </div>
</div>
<div  class="form-group row">
    <label for="" class="col-sm-2 control-label"> </label> 
    <div class="col-sm-5">
        <input type="text" class="form-control" id="startDate" name="startDate" placeholder="开始时间">
    </div>
    <div class="col-sm-5">
        <input type="text" class="form-control" id="endDate" name="endDate" placeholder="结束时间">
    </div>
</div>
<div  class="form-group row">
    <label for="bfb" class="col-sm-2 control-label"> </label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="bfb" id="bfb" value="" placeholder="百分比" required>
    </div>
</div>
<div class="form-group row">
    <label for="description" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
    <div class="col-sm-10">
        <textarea type="text" class="form-control" name="description" id="description" placeholder="任务描述" required></textarea>
    </div>
</div>

@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/rpa/KHYVis/add_immed.js')}}"></script>
@endslot
@endcomponent