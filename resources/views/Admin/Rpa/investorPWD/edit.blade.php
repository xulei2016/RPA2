@component('admin.widgets.addForm')    
    @slot('formContent')

    <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="InvestorPassword" placeholder="任务名称" required disabled>
            </div>
        </div>
        <div class="form-group">
            <label for="type" class="col-sm-2 control-label"><span class="must-tag">*</span>任务类型</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="type" name="type" value="1" @if($info->date) checked @endif>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>日期设定</label>
            <div class="col-sm-10">
                <div class="date @if(!$info->date) hidden @endif">
                    <input type="text" class="form-control" id="date" name="date" value="{{$info->date}}" placeholder="日期设定">
                </div>
                <div class="week @if($info->date) hidden @endif">
                    <label><input type="checkbox" class="select-single" name="week[]" value="0" @if(in_Array('0',$info->week))checked @endif>星期日</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="1" @if(in_Array('1',$info->week))checked @endif>星期一</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="2" @if(in_Array('2',$info->week))checked @endif>星期二</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="3" @if(in_Array('3',$info->week))checked @endif>星期三</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="4" @if(in_Array('4',$info->week))checked @endif>星期四</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="5" @if(in_Array('5',$info->week))checked @endif>星期五</label>
                    <label><input type="checkbox" class="select-single" name="week[]" value="6" @if(in_Array('6',$info->week))checked @endif>星期六</label>
                </div>
            </div>
        </div>
        <div class="form-group ">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>参数设置</label>
            <div class="col-sm-10">
                <div class="target_web">
                    <div class="row">
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="jsonuser" name="jsonuser" value="{{$info->data->user}}" placeholder="用户名">
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="jsonpwd" name="jsonpwd" value="{{$info->data->pwd}}" placeholder="密码">
                        </div>
                        <div class="col-xs-4">
                            <input type="text" class="form-control" id="jsondate" name="jsondate" value="{{$info->data->date}}" placeholder="指定查询日期">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>执行时间</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="implement_type" value="1" @if($info->implement_type) checked @endif>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <div class="row @if(!$info->implement_type) hidden @endif">
                    <div class="col-xs-9">
                        <input type="text" class="form-control" id="time" name="time" value="{{$info->time}}" placeholder="点击按钮添加时间">
                    </div>
                    <div class="col-xs-2">
                        <a href="javascript:void(0);" id="add_time" class="btn btn-sm btn-primary">00:00:00</a>
                    </div>
                </div>
                <div class="row @if($info->implement_type) hidden @endif">
                    <div class="col-xs-5">
                        <input type="text" class="form-control" id="start_time" name="start_time" value="{{$info->start_time}}" placeholder="开始时间">
                    </div>
                    <div class="col-xs-5">
                        <input type="text" class="form-control" id="end_time" name="end_time" value="{{$info->end_time}}" placeholder="结束时间">
                    </div>
                    <div class="col-xs-2">
                        <input type="text" class="form-control" id="mins" name="mins" value="{{$info->mins}}" placeholder="分割间隔(分钟)">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="description" id="description" placeholder="任务描述" required>{{$info->description}}</textarea>
            </div>
        </div>
        {{ method_field('PATCH')}}
        <input type="hidden" class="form-control" id="id" name="id" value="{{$info->id}}">
        <input type="hidden" class="form-control" id="jsondata" name="jsondata" value="{{$info->jsondata}}">

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/rpa/investorPWD/edit.js')}}"></script>
    @endslot
@endcomponent