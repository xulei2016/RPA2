@component('admin.widgets.editForm')
    @slot('formContent')
        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>通道名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ $gateway['name'] }}" placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="unique_name" class="col-sm-2 control-label"><span class="must-tag">*</span>通道代号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="unique_name" value="{{ $gateway['unique_name'] }}" id="unique_name" placeholder="名称">
            </div>
        </div>
        <div class="form-group row">
            <label for="available_list" class="col-sm-2 control-label"><span class="must-tag">*</span>可用列表</label>
            <div class="col-sm-10">
                <div class="show_available_list">
                    @foreach($gateway['available_list_copy'] as $available)
                        <span class="x-tag x-tag-sm x-tag-success {{ $available }}">{{ $available }}</span>
                    @endforeach
                </div>
                <div class="available_list">
                    @foreach($settings as $setting)
                        <label><input type="checkbox" value="{{ $setting->unique_name }}" @if(in_array($setting->unique_name, $gateway['available_list_copy'])) checked @endif>{{ $setting->name }}</label>
                    @endforeach
                </div>
                <input type="hidden" id="available_list" name="available_list" value="{{$gateway['available_list']}}">
            </div>
        </div>
        <div class="form-group row">
            <label for="status" class="col-sm-2 control-label"><span class="must-tag">*</span>状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="form-control switch" name="status" value="1" @if($gateway['unique_name']) checked @endif id="status" placeholder="状态">
            </div>
        </div>
        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>备注</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" style="min-height: 200px;">{{ $gateway['desc'] }}</textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10">
                <input type="hidden" class="form-control" id="id" name="id" value="{{ $gateway['id'] }}">
            </div>
        </div>

    @endslot
    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/sms/edit.js')}}"></script>
    @endslot
@endcomponent