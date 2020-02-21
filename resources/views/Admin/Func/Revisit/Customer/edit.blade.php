@component('admin.widgets.editForm', ['title' => '录音审核'])
    @slot('formContent')

        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label">回访状态<span class="must-tag">*</span></label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="status" value="3" checked>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">播放录音</label>
                <div class="col-sm-10" id="audioDiv">
                    <audio id="audio" controls="controls" src="" type="audio/mpeg">
{{--                        <source />--}}
                        设置不支持音频文件
                    </audio>
                </div>
            </div>
            <div class="row hidden">
                <label for="khyj" class="col-sm-2 control-label">失败原因</label>
                <div class="col-sm-10">
                    <textarea id="bz" name="bz" cols="60" rows="3"></textarea>
                </div>
            </div>
        </div>
        {{ method_field('PATCH') }}
        <input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
    @endslot

    @slot('formScript')
        <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
        <script src="{{URL::asset('/js/Admin/Func/Revisit/customer/edit.js')}}"></script>
        <script>

        </script>
    @endslot
@endcomponent