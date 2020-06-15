@component('admin.widgets.editForm')   
    @slot('title') 审核 @endslot
    @slot('edit_continue') checked @endslot
    @slot('formContent')

        <div class="card">
            <div class="card-header">个人信息-姓名: {{$customerManager->name}}, 资金账号: {{$info->zjzh}}</div>
        </div>
        <div class="card">
        <div class="card-header">身份证地址核验</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 control-label">身份证正面照</label>
                <div class="col-sm-8" data-fancybox href="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_zm }}">
                    <img src="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_zm }}" alt="身份证正面照" width="100%">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 control-label mt4">普通识别地址</label>
                <div class="col-sm-8">
                    <a class="btn btn-primary choose btn-block text-left" id="general-address" item="address" val="{{$info->address}}">{{ $info->address }}</a>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 control-label mt4">深度识别地址</label>
                <div class="col-sm-8">
                    <a class="btn btn-success choose btn-block text-left" id="deep-address" item="address" val="{{$info->address_deep}}">{{ $info->address_deep }}</a>
                </div>
            </div>

            <div class="form-group row">
                <label for="address_final" class="col-sm-2 control-label mt4">最终地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="address_final" id="address_final" value="{{ $info->address == $info->address_deep?$info->address:'' }}" placeholder="最终地址" required>
                </div>
            </div>
        </div>
    </div>
        <div class="card"  >
        <div class="card-header">身份证有效期核验</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 control-label">身份证反面照</label>
                <div class="col-sm-8"  data-fancybox href="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_fm }}">
                    <img src="/admin/rpa_new_monitor_picture/showImg?url={{ $customerImg->sfz_fm }}" alt="身份证正面照" width="100%">
                </div>
            </div>
            <div class="form-group row">
                <label for="address_final" class="col-sm-2 control-label mt4">最终有效期</label>
                <div class="col-sm-8 row">
                    <input type="text" id="start_at_final" style="margin-left: 7px;width: 48%;" class="form-control text-center" name="start_at_final" item="{{ $customerManager->sfz_date_begin }}" value="{{ $customerManager->sfz_date_begin }}" />
                    <span style="margin-left: 3px;margin-right: 3.3px;line-height: 34px;"> - </span>
                    <input type="text" id="end_at_final" style="width: 48%"  class="form-control text-center" name="end_at_final" item="{{ $customerManager->sfz_date_end }}" value="{{ $customerManager->sfz_date_end }}" />
                </div>
            </div>
            
        </div>
    </div>

    <div>
        <div class="card">
            <div class="card-header">附加信息</div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="remark" class="col-sm-2 control-label mt4">备注</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="remark" id="remark" value="{{ $info->remark }}"  placeholder="备注">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="id" value="{{ $info->id }}">
    @endslot

    @slot('formScript')
        <style>
            .mt4 {
                margin-top: 4px;
            }
            a.btn{
                cursor: pointer;
            }
            .swal2-popup #swal2-content{
                text-align:center;
            }
        </style>
        <link rel="stylesheet" href="{{ URL::asset('/include/fancybox/fancybox.css')}}">
        <script src="{{URL::asset('/js/admin/Func/NewMonitorPicture/edit.js')}}"></script>
        <script src=" {{ URL::asset('/include/fancybox/fancybox.js')}} "></script>
        <script src="{{URL::asset('/include/jquery-mousewheel/jquery.mousewheel.js/')}}"></script>
        <script src="{{URL::asset('/include/jquery-zoommarker/js/zoom-marker.js')}}"></script>
    @endslot
@endcomponent