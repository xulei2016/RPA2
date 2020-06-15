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
                        <input class="form-control" type="number" id="rate" name="rate" value="{{ $flow->rate }}" required 
                        @if($flow->type == 1) readonly @endif >
                    </div>
                </div>
                <br>
                <div class="row">
                    <label for="special_rate" class="col-sm-2 control-label"><span class="must-tag">*</span>特殊比例</label>
                    <div class="col-sm-10">
                        <input type="checkbox" class="my-switch" id="special_rate" name="special_rate" value="0" checked>
                    </div>
                </div>
                <br>
                <div class="row">
                    <label for="manager_number" class="col-sm-2 control-label"><span class="must-tag">*</span>客户经理编号</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="manager_number" name="manager_number" value="{{ $flow->manager_number }}" readonly>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <label for="number" class="col-sm-2 control-label"><span class="must-tag">*</span>居间编号</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="number" id="number" name="number" value="{{ $flow->number }}" required>
                    </div>
                </div>
                <br>
                <div class="row">
                    <label for="is_review" class="col-sm-2 control-label"><span class="must-tag">*</span>是否加入回访</label>
                    <input type="checkbox" class="my-switch" id="is_review" name="is_review" value="1" checked>
                </div>
                <br/>
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
                            <input class="back_step" data-name="{{ $v->name }}" name="back[]" type="checkbox" id="{{ $v->url }}" value="{{ $v->url }}">{{ $v->name }}&nbsp;&nbsp;
                        </label>
                    @endforeach
                </div>
                <br>
                <div class="row">
                    <label for="is_send" class="col-sm-2 control-label"><span class="must-tag">*</span>是否发送短信</label>
                    <input type="checkbox" class="my-switch" id="is_send" name="is_send" value="1" checked>
                </div>
                <br>
                <div class="row">
                    <label for="send_tpl" class="col-sm-2 control-label"><span class="must-tag">*</span>短信模板</label>
                    <textarea class="col-sm-10 form-control" name="send_tpl" id="send_tpl" cols="60" rows="3" required></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/mediator/check.js')}}"></script>
    @endslot
@endcomponent