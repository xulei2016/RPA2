@component('admin.widgets.editForm')
@slot('title')
        审核
    @endslot

    @slot('formContent')
    @if($data->status != 1)
        <link rel="stylesheet" href="{{ URL::asset('/include/fancybox/fancybox.css')}}">
        <div class="row">
                    <label for="khyj" class="col-sm-2 control-label">客户信息</label>
                    <div class="col-sm-10">
                        <span style="font-size: 18px;color:gray; font-weight: bold">{{$data->name}} {{$data->fundsNum}}</span>
                    </div>
                </div>
                <div class="row" style="display:flex;">
                    <label for="khyj" class="col-sm-2 control-label">证件照</label>
                    <div class="col-sm-3" style="text-align:center">
                    <img class="pic pic-select" src="/admin/zt/storage?key={{$data->certificates_positive}}"/>
                    <div class="m-des">正面</div>
            
                    <style>
                    .m-des{
                        text-align: center;
                        color: gray
                    }

                    .pic{
                            max-width: 180px;
                            height: 130px;
                            cursor: pointer;
                            margin-top: 10px;
                            cursor: pointer;
                        }

                        .pic-select{
                            border: 2px solid #13ce66;
                        }
                    </style>
                    </div>
                    <div class="col-sm-3" style="text-align:center">
                    <img class="pic"  src="/admin/zt/storage?key={{$data->certificates_negative}}"/>
                    <div class="m-des">反面</div>
                    </div>
                </div>
                <div class="row">
                    <label for="khyj" class="col-sm-2 control-label">证件照大图</label>
                    <div class="col-sm-10">
        
                        <div id="bigPicDiv" data-fancybox href="/admin/zt/storage?key={{$data->certificates_positive}}">
                            <img id="bigPic" style="padding-top: 10px;max-width: 600px;cursor: pointer;" src="/admin/zt/storage?key={{$data->certificates_positive}}" alt="从业资格证"  title="从业资格证">
                        </div>
                        
                    </div>
                </div>

            <div class="form-group row" style="margin-top:10px;">
                <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>是否办理</label>
                <div class="col-sm-10">
                    <input type="checkbox" class="my-switch" id="implement_type" name="status" value="1" checked>
                </div>
            </div>
            <div class="form-group">
                <div class="reason hidden">
                    <div class="row">
                        <label for="back" class="col-sm-2 control-label"><span class="must-tag">*</span>原因</label>   
                        @foreach($reasons as $v)
                            <label class="checkbox-inline" for="{{$v}}">
                                <input class="reasons" data-name="{{ $v }}" name="reason[]" type="checkbox" id="{{ $v }}" value="{{ $v }}">{{ $v }}&nbsp;&nbsp;
                            </label>
                        @endforeach
                    </div>
        
                    <div class="row">
                        <label for="send_tpl" class="col-sm-2 control-label"><span class="must-tag">*</span>短信模板</label>
                        <textarea class="col-sm-10 form-control" name="send_tpl" id="send_tpl" cols="60" rows="3" required></textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" class="form-control" id="id" name="id" value="{{$data->id}}">
            <input type="hidden" class="form-control" id="type" name="type" value="accept">
            <input type="hidden" class="form-control" id="m-type" name="m-type" value="{{$data->business_type}}">

            <script src=" {{ URL::asset('/include/fancybox/fancybox.js')}} "></script>
        @else
        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>办理结果</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="status" value="1" checked>
            </div>
        </div>
        <div class="form-group">
            <div class="reason hidden">
                <div class="row">
                    <label for="back" class="col-sm-2 control-label"><span class="must-tag">*</span>失败原因</label>   
                    @foreach($reasons as $v)
                        <label class="checkbox-inline" for="{{$v}}">
                            <input class="reasons" data-name="{{ $v }}" name="reason[]" type="checkbox" id="{{ $v }}" value="{{ $v }}">{{ $v }}&nbsp;&nbsp;
                        </label>
                    @endforeach
                </div>
    
                <div class="row">
                    <label for="send_tpl" class="col-sm-2 control-label"><span class="must-tag">*</span>短信模板</label>
                    <textarea class="col-sm-10 form-control" name="send_tpl" id="send_tpl" cols="60" rows="3" required></textarea>
                </div>
            </div>
        </div>
            
        <input type="hidden" class="form-control" id="id" name="id" value="{{$data->id}}">
        <input type="hidden" class="form-control" id="type" name="type" value="success">
        <input type="hidden" class="form-control" id="m-type" name="m-type" value="{{$data->business_type}}">
        @endif
    @endslot

    @slot('formScript')
        @if($data->status != 1)
            <script src="{{URL::asset('/js/admin/func/SeatApply/accept.js')}}"></script>
        @else
            <script src="{{URL::asset('/js/admin/func/SeatApply/edit.js')}}"></script>        
        @endif
    @endslot
@endcomponent