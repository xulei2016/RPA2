@component('admin.widgets.editForm')    
    @slot('formContent')

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label">身份证图片</label>
        <div class="col-sm-10">
            <img src="{{ $sfz_zm }}" alt="身份证正面照">
        </div>
    </div>

    <div class="form-group row">
        <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>最终地址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{ $info->address_final }}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="state" class="col-sm-2 control-label"><span class="must-tag">*</span>复核</label>
        <div class="col-sm-10">
            <input type="checkbox" class="form-control" name="state" id="state" value="2" checked>
        </div>
    </div>
    <input type="hidden" id="id" value="{{ $info->id }}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/Recognition/review.js')}}"></script>
    @endslot
@endcomponent