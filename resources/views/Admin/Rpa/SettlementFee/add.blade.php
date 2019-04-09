@component('admin.widgets.addForm')    
    @slot('formContent')

    <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="SettlementFee" placeholder="任务名称" required disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="col-sm-2 control-label"><span class="must-tag">*</span>任务类型</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="type" name="type" value="1">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>日期设定</label>
            <div class="col-sm-10">
                <div class="date hidden">
                    <input type="text" class="form-control" id="date" name="date" placeholder="日期设定">
                </div>
                <div class="week">
                    <label><input type="checkbox" class="select-single" name="week[]" value="0">星期日</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="1">星期一</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="2">星期二</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="3">星期三</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="4">星期四</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="5">星期五</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="6">星期六</label>
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数设定</label>
            <div class="col-sm-10">
                <div class="target_web">
                    <div class="row">
                        <div class="col-xs-12">
                            <label><input type="radio" name="jsondate" value="8200" checked> 8200</label>
                            <label><input type="radio" name="jsondate" value="8300"> 8300</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>执行时间</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="implement_type" value="1">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <div class="row hidden">
                    <div class="col-xs-9">
                        <input type="text" class="form-control" id="time" name="time" placeholder="点击按钮添加时间">
                    </div>
                    <div class="col-xs-2">
                        <a href="javascript:void(0);" id="add_time" class="btn btn-sm btn-primary">00:00:00</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <input type="text" class="form-control" id="start_time" name="start_time" placeholder="开始时间">
                    </div>
                    <div class="col-xs-5">
                        <input type="text" class="form-control" id="end_time" name="end_time" placeholder="结束时间">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control" id="mins" name="mins" placeholder="分割间隔(分钟)">
                    </div>
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
        <script src="{{URL::asset('/js/admin/rpa/SettlementFee/add.js')}}"></script>
    @endslot
@endcomponent