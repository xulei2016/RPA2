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
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/sys_version_update/create" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                                </a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="desc" placeholder="简介">
                                </div>
                                <div class="col-sm-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="">类型(全部)</option>
                                        <option value="1">正常更新)</option>
                                        <option value="2">版本升级</option>
                                        <option value="3">紧急维护</option>
                                    </select>
                                </div>
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>
    <script src="{{URL::asset('/js/admin/base/versionUpdate/index.js')}}"></script>
@endsection