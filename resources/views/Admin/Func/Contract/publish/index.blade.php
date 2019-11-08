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
                                <!-- <a class="btn btn-primary btn-sm cale"  title="数据日历">
                                    数据日历
                                </a> -->
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_contract_detail"  title="返回">
                                    返 回
                                </a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" name="date" id="date" class="form-control" placeholder="日期">
                                </div>
                                <div class="col-sm-2">
                                    <select name="jys_id" id="jys_id" class="form-control">
                                        <option value="">交易所(未选择)</option>
                                        @foreach($jys as $v)
                                            <option value="{{$v->id}}">{{$v->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="">类型(未选择)</option>
                                        <option value="1">上下市</option>
                                        <option value="2">运行中调整</option>
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
    <script src="{{URL::asset('/include/simple-calendar/javascripts/simple-calendar.js')}}"></script>
    <script src="{{URL::asset('/js/admin/func/contract/publish/index.js')}}"></script>
@endsection