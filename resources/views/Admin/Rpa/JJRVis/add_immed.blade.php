@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="MediatorVisit" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group ">
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
<div class="form-group">
    <label for="description" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
    <div class="col-sm-10">
        <textarea type="text" class="form-control" name="description" id="description" placeholder="任务描述" required></textarea>
    </div>
</div>

@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/rpa/JJRVis/add_immed.js')}}"></script>
@endslot
@endcomponent