@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar', ['operation_show' => false])
                        @slot('listsOperation')
                        @endslot

                        @slot('operation')
                            <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_customer_funds_search/varietyadd" title="新增" onclick="operation($(this));">
                                <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加品种</span>
                            </a>
                            <a class="btn btn-primary btn-sm" href="/admin/rpa_customer_funds_search">客户列表</a>
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
    </div>

    <script src="{{URL::asset('/js/admin/func/Oabremind/varietyList.js')}}"></script>
@endsection