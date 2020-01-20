<div class="card card-primary card-outline" style="min-height: 500px;">
    <div class="card-header">

        <h3 class="text-center">{{$info->flowName}}-{{$info->nodeName}}</h3>
        <hr>
        <div class="operation ">
            <span>{{$info->instanceName}}</span>
            <span class="pull-right">
                @if($record && 9 != $info->status)
                    <a record-id="{{$record->id}}" href="javascript:;" class="btn btn-primary btn-sm" item-data="confirm">批准</a>
                    <a record-id="{{$record->id}}" href="javascript:;" class="btn btn-default btn-sm" item-data="back">打回</a>
                @endif
                <a href="javascript:;" class="btn btn-default btn-sm" item-data="transfer">转发</a>
            </span>
        </div>
    </div>
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active flowForm" href="#flowForm" data-toggle="tab">流程表单</a></li>
            <li class="nav-item"><a class="nav-link flowPic" href="#flowPic" data-toggle="tab">流程图</a></li>
{{--            <li class="nav-item"><a class="nav-link" href="#flowResource" data-toggle="tab">相关资源</a></li>--}}
        </ul>
        <div class="card-body  tab-content">
            <div class="tab-pane active" id="flowForm">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <td style="background: #e7f3fc">标题</td>
                        <td>{{$info->instanceName}}</td>
                        <td style="background: #e7f3fc">流程编号</td>
                        <td>{{$info->work_num}}</td>
                    </tr>

                    @foreach($formList as $form)
                        <tr>
                            <td colspan="1" style="background: #e7f3fc">{{$form->field_showname}}</td>
                            <td colspan="3">
                                @if($form->field_type == 'image')
                                        <a href="javascript:;" class="fieldImg file" name="{{$form->field_showname}}"
                                           type="image" url="{{$form->field_value}}">{{$form->field_showname}}</a>
                                    @elseif($form->field_type == 'file')
                                        <a href="javascript:;" class="fieldFile file" url="{{$form->field_value}}" type="file">{{$form->field_showname}}</a>
                                    @else
                                        {{$form->field_value}}
                                    @endif
                            </td>
                        </tr>
                    @endforeach


                </table>
                <div>
                    <form action="" id="flowForm">
                        <textarea class="form-control" id="remark"></textarea>
                        <input type="hidden" name="id" id="id" value="{{$info->id}}">
                    </form>
                </div>
                <div class="card">
                    <div class="card-header">
                        流转意见
                    </div>
                    <div class="card-body">
                        <ul class="products-list product-list-in-card">
                            @foreach($recordList as $v)
                                <li class="item">
                                    <div class="product-img">
                                        <img src="/{{$v->headImg}}" alt="">
                                    </div>
                                    <div class="product-info">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="product-title">{{$v->user_name}}</div>
                                                <span class="product-description">{{$v->dept_name}}</span>
                                            </div>
                                            <div class="col-sm-7">
                                                <div class="product-title">@if($v->remark) {!! $v->remark !!} @else 无 @endif</div>
                                                <span class="product-description">接收者:{{$v->nextAdminNames}}</span>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="product-title">{{$v->nodeName}} / {{$v->statusName}}</div>
                                                <span class="product-description">{{$v->bl_time}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="flowPic">
                <iframe id="flowIframe" src="" frameborder="0" height="500" width="100%">
                    <div>流程加载中,请稍后</div>
                </iframe>
            </div>
{{--            <div class="tab-pane" id="flowResource">--}}
{{--                <div>相关资源</div>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
<script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>

<script src="{{URL::asset('js/admin/base/flow/mine/show.js')}}"></script>
