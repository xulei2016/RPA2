@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                            @endslot
                            @slot('operation')
                                <a class="btn btn-primary btn-sm" href="javascript:history.go(-1);"  title="返回">
                                    返 回
                                </a>
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_plugin_version/create?id={{$id}}" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                                </a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="name" placeholder="名称">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="version" placeholder="版本">
                                </div>
                                    <input type="hidden" name="pid" id="pid" value="{{$id}}" />
                                @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/plugin/version/index.js')}}"></script>
@endsection