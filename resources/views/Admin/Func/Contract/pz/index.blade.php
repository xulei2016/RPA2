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
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_contract_pz/create" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                                </a>
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_contract_detail"  title="返回">
                                    返 回
                                </a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <select name="jys_id" id="jys_id" class="form-control">
                                        <option value="">未选择</option>
                                        @foreach($jys as $v)
                                            <option value="{{$v->id}}">{{$v->name}}</option>
                                        @endforeach
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
    <script src="{{URL::asset('/js/admin/func/contract/pz/index.js')}}"></script>
@endsection