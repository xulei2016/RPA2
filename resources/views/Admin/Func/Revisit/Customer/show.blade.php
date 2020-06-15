@component('admin.widgets.viewForm', ['title' => '查看审核'])
    @slot('formContent')

        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label">客户姓名<span class="must-tag">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $record->name }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label">资产账号<span class="must-tag">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $record->fundsNum }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label">居间人<span class="must-tag">*</span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{ $record->sync_jjr_name }} -- {{ $record->sync_jjr_num }}" disabled>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label class="col-sm-2 control-label">播放录音</label>
                <div class="col-sm-10" id="audioDiv">
                    <audio id="audio" controls="controls" src="" type="audio/mpeg">
                        设置不支持音频文件
                    </audio>
                </div>
            </div>
        </div>
        <input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/Admin/Func/Revisit/customer/show.js')}}"></script>
    @endslot
@endcomponent