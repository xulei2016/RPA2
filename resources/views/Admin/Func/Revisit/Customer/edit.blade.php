@component('admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>回访状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="status" value="1" checked>
            </div>
            <div class="row">
                <label for="khyj" class="col-sm-2 control-label">录音文件</label>
                <div class="col-sm-10">
                    <audio id="aaa" src="">播放</audio>
                </div>

                <label for="khyj" class="col-sm-2 control-label">备注</label>
                <div class="col-sm-10">
                    <textarea id="bz" name="bz" cols="60" rows="3"></textarea>
                </div>
            </div>
        </div>
        {{ method_field('PATCH') }}
        <input type="hidden" class="form-control" id="id" name="id" value="{{$data->id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/Admin/Func/Reviewtables/edit.js')}}"></script>
    @endslot
@endcomponent