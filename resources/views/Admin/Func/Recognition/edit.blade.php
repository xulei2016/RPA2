@component('admin.widgets.editForm')   
    @slot('title') 审核 @endslot
    @slot('edit_continue') checked @endslot
    @slot('formContent')

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label">身份证图片</label>
        <div class="col-sm-10">
            <img src="{{ $sfz_zm }}" alt="身份证正面照" width="600" height="400">
        </div>
    </div>

    <div class="form-group row">
        <label for="" class="col-sm-2 control-label">识别地址</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" value="{{ $info->address }}" readonly>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-sm btn-primary" id="address" type="button"><i class="fa fa-hand-o-left"></i> 选我</button>
        </div>
    </div>

    <div class="form-group row">
        <label for="" class="col-sm-2 control-label">深度识别地址</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" value="{{ $info->address_deep }}" readonly>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-sm btn-primary" id="address_deep" type="button"><i class="fa fa-hand-o-left"></i> 选我</button>
        </div>
    </div>

    <div class="form-group row">
        <label for="address_final" class="col-sm-2 control-label"><span class="must-tag">*</span>最终地址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="address_final" id="address_final" @if($info->address == $info->address_deep) value="{{ $info->address_deep }}" @endif placeholder="最终地址" required>
        </div>
    </div>

    <div class="form-group row">
        @if($info->address == $info->address_deep) 
            <span class="text-primary">俩次识别结果一致</span>
        @else
            <span class="text-danger">俩次识别结果不一致</span>
        @endif
    </div>

    <input type="hidden" id="id" value="{{ $info->id }}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/Recognition/edit.js')}}"></script>
    @endslot
@endcomponent