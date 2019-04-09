@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="{{$info->name}}" placeholder="任务名称">
    </div>
</div>
<div class="form-group">
    <label for="state" class="col-sm-2 control-label"><span class="must-tag">*</span>任务状态</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="state" id="state" value="{{$info->state}}" placeholder="状态">
    </div>
</div>
<div class="form-group ">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数</label>
    <div class="col-sm-10">
        <div class="target_data">
            @foreach($info->data as $k => $data)
                @if($loop->first)
                    <div class="row">
                        <div class="col-xs-7">
                            <input type="text" class="form-control" id="data_key" name="data_key" value="{{$k}}" placeholder="参数名">
                        </div>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" id="data_value" name="data_value" value="{{$data}}" placeholder="参数值">
                        </div>
                        <div class="col-xs-2">
                            <a href="javascript:void(0);" id="add_data" class="btn btn-sm btn-primary">增加</a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-xs-7">
                            <input type="text" class="form-control" id="data_key" name="data_key" value="{{$k}}" placeholder="参数名">
                        </div>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" id="data_value" name="data_value" value="{{$data}}" placeholder="参数值">
                        </div>
                        <div class="col-xs-2">
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger del_data">删除</a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
<div class="form-group">
    <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>执行时间</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" id="time" name="time" value="{{$info->time}}" placeholder="点击修改时间">
    </div>
</div>
<div class="form-group">
    <label for="tid" class="col-sm-2 control-label"><span class="must-tag">*</span>TID</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="tid" id="tid" value="{{$info->tid}}" placeholder="tid">
    </div>
</div>
<input type="hidden" class="form-control" id="id" name="id" value="{{$info->id}}">
<input type="hidden" class="form-control" id="jsondata" name="jsondata" value="{{$info->jsondata}}">

@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/rpa/center/editQueue.js')}}"></script>
@endslot
@endcomponent