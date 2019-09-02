@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group row">
    <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="name" id="name" value="zwtx" placeholder="任务名称" required disabled>
    </div>
</div>
<div class="form-group row">
    <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>目标站点</label>
    <div class="col-sm-10">
        <div class="target_web">
            @if($jsondata)
                @foreach($jsondata as $k=>$v)
                    <div class="row weblist">
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="web" placeholder="站点名称" value="{{$k}}">
                        </div>
                         <div class="col-xs-3">
                             <input type="text" class="form-control" name="num" placeholder="文章数量" value="{{$v}}">
                         </div>
                        @if($loop->first)
                            <div class="col-xs-2">
                                <a href="javascript:void(0);" id="add_web" class="btn btn-sm btn-success">增加</a>
                            </div>
                        @else
                            <div class="col-xs-2">
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger del_web">删除</a>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="row weblist">
                    <div class="col-xs-7">
                        <input type="text" class="form-control" name="web" placeholder="例如：https://wallstreetcn.com/" required>
                    </div>
                    <div class="col-xs-3">
                        <input type="text" class="form-control" name="num" placeholder="文章数量" required>
                    </div>
                    <div class="col-xs-2">
                        <a href="javascript:void(0);" id="add_web" class="btn btn-sm btn-success">增加</a>
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
<script src="{{URL::asset('/js/admin/rpa/news/add_immed.js')}}"></script>
@endslot
@endcomponent