@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">短信平台管理
                            <a class="btn btn-default btn-sm" href="/admin/sys_sms/sms_setting">刷新</a>
                            <a class="btn btn-default btn-sm" href="javascript:history.go(-1);">返回</a>
                        </h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($Settings as $Setting)
                                <div class="col-12 col-sm-4 col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h5 class="card-title">{{ $Setting -> name }}</h5>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                                            class="fa fa-minus"></i></button>
                                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p>
                                                        @if($Setting->status)
                                                            <span class="x-tag x-tag-sm x-tag-success">已开启</span>
                                                        @else
                                                            <span class="x-tag x-tag-sm x-tag-danger">已禁用</span>
                                                        @endif
                                                    </p>

                                                    <p><b>管理地址: </b><a href="{{ $Setting -> managerAddress }}">{{ $Setting -> managerAddress }}</a>

                                                    <p><b>剩余短信条数: </b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="text-right">
{{--                                                <a href="#" class="btn btn-sm bg-teal"><i class="fa fa-comments"></i> 刷新</a>--}}
                                                <a  href="javascript:void(0);" url="/admin/sys_sms/editSmsSetting/{{ $Setting -> id }}" title="新增" onclick="operation($(this))" class="btn btn-sm btn-primary"><i class="fa fa-user"></i> 设置</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="col-3 col-sm-3 col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <a class="btn btn-app" href="javascript:void(0);" url="/admin/sys_sms/addSmsSetting" title="新增" onclick="operation($(this))">
                                            <i class="fa fa-plus"></i>添加
                                        </a>
                                        <a class="btn btn-app" href="javascript:void(0);" url="/admin/sys_sms/testSms" title="新增" onclick="operation($(this))">
                                            <i class="fa fa-play"></i>测试
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">通道列表</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                            @endslot

                            @slot('operation')
                                <a class="btn btn-primary btn-sm" href="javascript:void(0);" url="/admin/sys_sms/create" title="新增" onclick="operation($(this))">+ 新增通道</a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{URL::asset('/js/admin/base/sms/sms_setting_list.js')}}"></script>
@endsection