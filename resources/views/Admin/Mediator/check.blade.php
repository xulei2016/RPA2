@component('admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>审核状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="status" value="1" checked>
            </div>
        </div>
        <div class="form-group">
            <div class='yes'>
                <div class="row">
                    <label for="rate" class="col-sm-2 control-label"><span class="must-tag">*</span>居间比例</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="rate" name="rate" value="{{ $flow->rate }}" required>
                    </div>
                </div>
                <br>
                <div class="row">
                    <label for="number" class="col-sm-2 control-label"><span class="must-tag">*</span>居间编号</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="number" name="number" value="{{ $flow->number }}" required>
                    </div>
                </div>
                <br>
                <div class="row">
                    <label for="remark" class="col-sm-2 control-label">备注</label>
                    <div class="col-sm-10">
                        <textarea id="remark" class="form-control" name="remark" cols="60" rows="3">{{ $flow->remark }}</textarea>
                    </div>
                </div>
            </div>
            <div class="no hidden">
                <div class="row">
                    <label for="back" class="col-sm-2 control-label"><span class="must-tag">*</span>打回步骤</label>
                    @foreach($steps as $v)
                        <label class="checkbox-inline" for="{{ $v->url }}">
                            <input name="back[]" type="checkbox" id="{{ $v->url }}" value="{{ $v->url }}">{{ $v->name }}&nbsp;&nbsp;
                        </label>
                    @endforeach
                </div>
                <div class="row">
                    <label for="reason" class="col-sm-2 control-label"><span class="must-tag">*</span>失败原因</label>
                    <textarea class="col-sm-10 form-control" name="reason" id="reason" cols="60" rows="3" required></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/mediator/check.js')}}"></script>
    @endslot
@endcomponent