@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="NewVideoHints" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group ">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>目标站点</label>
    <div class="col-sm-10">
        <div class="target_web">
            @if($jsondata)
                @foreach($jsondata as $k => $data)
                    @if($loop->first)
                        <div class="row">
                            <div class="col-xs-10">
                                <input type="text" class="form-control" id="data" name="data" value="{{$data}}" placeholder="请输入营业部">
                            </div>
                            <div class="col-xs-2">
                                <a href="javascript:void(0);" id="add_web" class="btn btn-sm btn-primary">增加</a>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-xs-10">
                                <input type="text" class="form-control" name="data" value="{{$data}}" placeholder="请输入营业部">
                            </div>
                            <div class="col-xs-2">
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger del_web">删除</a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="row">
                    <div class="col-xs-10">
                        <input type="text" class="form-control" id="data" name="data" placeholder="请输入营业部">
                    </div>
                    <div class="col-xs-2">
                        <a href="javascript:void(0);" id="add_web" class="btn btn-sm btn-primary">增加</a>
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
<script src="{{URL::asset('/js/admin/rpa/NewVideoHints/add_immed.js')}}"></script>
@endslot
@endcomponent