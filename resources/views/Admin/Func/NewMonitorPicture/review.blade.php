@component('admin.widgets.editForm')   
    @slot('title') 复核 @endslot
    @slot('edit_continue') checked @endslot 
    @slot('formContent')

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label">身份证正面</label>
        <div class="col-sm-10" data-fancybox href="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_zm }}">
            <img src="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_zm }}" alt="身份证正面" width="100%" />
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>最终地址</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" readonly name="address_final" id="address_final" value="{{ $info->address_final }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label">身份证反面</label>
        <div class="col-sm-10" data-fancybox href="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_fm }}">
            <img src="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_fm }}" alt="身份证反面" width="100%" />
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>有效期开始时间</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" readonly name="start_at_final" id="start_at_final" value="{{ $info->start_at_final }}">
        </div>
    </div>
    <div class="form-group row">
        <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>有效期结束时间</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" readonly name="end_at_final" id="end_at_final" value="{{ $info->end_at_final }}">
        </div>
    </div>

    <div class="form-group row">
        <label for="state" class="col-sm-2 control-label"><span class="must-tag">*</span>复核</label>
        <div class="col-sm-10">
            <input type="checkbox" class="form-control" name="check_status" id="check_status" value="2" checked>
        </div>
    </div>
    <input type="hidden" id="id" value="{{ $info->id }}" />

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/NewMonitorPicture/review.js')}}"></script>
    @endslot
@endcomponent