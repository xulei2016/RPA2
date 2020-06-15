@component('admin.widgets.editForm')   
    @slot('title') 审核 @endslot
    @slot('edit_continue') checked @endslot
    @slot('formContent')

        <div class="card">
            <div class="card-header">个人信息-姓名: {{$info->KHXM}}, 资金账号: {{$info->ZJZH}}</div>
        </div>
        <div class="card" style="@if(!in_array($info->status, [2, 3])) display:none @endif">
        <div class="card-header">身份证地址核验</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 control-label">身份证正面照</label>
                <div class="col-sm-8" data-fancybox href="/admin/rpa_monitor_picture/showImg?url={{ $sfz_zm }}">
                    <img src="/admin/rpa_monitor_picture/showImg?url={{ $sfz_zm }}" alt="身份证正面照" width="100%">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 control-label mt4">crm中地址</label>
                <div class="col-sm-8">
                    <a class="btn btn-primary choose btn-block text-left" id="crm-address" item="address" val="{{$info->SFZDZ}}">{{ $info->SFZDZ }}</a>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 control-label mt4">识别地址</label>
                <div class="col-sm-8">
                    <a class="btn btn-success choose btn-block text-left" id="baidu-address" item="address" val="{{$info->address}}">{{ $info->address }}</a>
                </div>
            </div>

            <div class="form-group row">
                <label for="address_final" class="col-sm-2 control-label mt4">最终地址</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="address_final" id="address_final" value="{{ $info->address == $info->SFZDZ?$info->address:'' }}" placeholder="最终地址" required>
                </div>
            </div>
        </div>
    </div>
        <div class="card" style="@if(!in_array($info->status, [2, 4])) display:none @endif"  >
        <div class="card-header">身份证有效期核验</div>
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 control-label">身份证反面照</label>
                <div class="col-sm-8"  data-fancybox href="/admin/rpa_monitor_picture/showImg?url={{ $sfz_fm }}">
                    <img src="/admin/rpa_monitor_picture/showImg?url={{ $sfz_fm }}" alt="身份证正面照" width="100%">
                </div>
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 control-label mt4">crm中有效期</label>
                <div class="col-sm-8">
                    <a class="btn btn-primary choose" style="width: 48%" item="start_at" val="{{ $info->crm_zjksrq }}">{{ $info->crm_zjksrq }}</a>
                    -
                    <a class="btn btn-primary choose" style="width: 48%" item="end_at" val="{{ $info->crm_zjjsrq }}">{{ $info->crm_zjjsrq }}</a>
                </div>
            </div>

            <div class="form-group row">
                <label for="" class="col-sm-2 control-label mt4">识别有效期</label>
                <div class="col-sm-8">
                    <a class="btn btn-success choose" style="width: 48%" item="start_at" val="{{$info->start_at}}">{{ $info->start_at?:'无' }}</a>
                    -
                    <a class="btn btn-success choose" style="width: 48%" item="end_at" val="{{$info->end_at}}">{{ $info->end_at?:'无' }} @if($info->end_at == '20991231')(长期)@endif</a>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_final" class="col-sm-2 control-label mt4">最终有效期</label>
                <div class="col-sm-8 row">
                    <input type="text" id="start_at_final" style="margin-left: 7px;width: 48%;" class="form-control text-center" name="start_at_final" value="{{ $info->start_at == $info->crm_zjksrq ? $info->start_at: ''}}" />
                    <span style="margin-left: 3px;margin-right: 3.3px;line-height: 34px;"> - </span>
                    <input type="text" id="end_at_final" style="width: 48%"  class="form-control text-center" name="end_at_final" value="{{ $info->end_at == $info->crm_zjjsrq ? $info->end_at: '' }}" />
                </div>
            </div>
            
        </div>
    </div>

<<<<<<< .mine
||||||| .r2072
    <div>
        <div class="card">
            <div class="card-header">附加信息</div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="remark" class="col-sm-2 control-label mt4">备注</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="remark" id="remark"  placeholder="备注">
                    </div>
                </div>
            </div>
        </div>
    </div>
=======
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
>>>>>>> .r2113
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
        <script src="{{URL::asset('/js/admin/Func/MonitorPicture/edit.js')}}"></script>
        <script src=" {{ URL::asset('/include/fancybox/fancybox.js')}} "></script>
        <script src="{{URL::asset('/include/jquery-mousewheel/jquery.mousewheel.js/')}}"></script>
        <script src="{{URL::asset('/include/jquery-zoommarker/js/zoom-marker.js')}}"></script>
    @endslot
@endcomponent