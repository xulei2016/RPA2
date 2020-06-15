@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar', ['operation_show' => false])
                            @slot('listsOperation')
                                <li><a class="dropdown-item" href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                            @endslot
                            @slot('operation')
                                <a class="btn btn-success btn-sm" url="/admin/rpa_downloadPDF/immedtasks" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;立即任务</span>
                                </a>
                                <a class="btn btn-success btn-sm" url="/admin/rpa_downloadPDF/create" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加任务</span>
                                </a>
                            @endslot
                        @endcomponent

                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="{{URL::asset('/js/admin/rpa/profession/index.js')}}"></script>
@endsection