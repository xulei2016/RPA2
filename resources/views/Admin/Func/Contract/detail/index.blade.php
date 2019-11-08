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
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_contract_detail/create" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                                </a>
                                <a class="btn btn-primary btn-sm tree-ntstable-add" href="/admin/rpa_contract_dict" title="基本配置">
                                    基本配置
                                </a>
                                <a class="btn btn-primary btn-sm tree-ntstable-add" href="/admin/rpa_contract_jys" title="交易所管理">
                                    交易所管理
                                </a>
                                <a class="btn btn-primary btn-sm tree-ntstable-add" href="/admin/rpa_contract_pz" title="品种管理">
                                    品种管理
                                </a>
                                <a class="btn btn-primary btn-sm tree-ntstable-add" href="/admin/rpa_contract_receiver" title="人员管理">
                                    人员管理
                                </a>
                                <a class="btn btn-primary btn-sm tree-ntstable-add" href="/admin/rpa_contract_publish" title="推送信息查看">
                                    普通日期列表
                                </a>
                                <a class="btn btn-primary btn-sm tree-ntstable-add" href="/admin/rpa_contract_extra" title="指定日期列表">
                                    指定日期列表
                                </a>
                                <a class="btn btn-success btn-sm tree-ntstable-add test-email" href="javascript:;" title="邮件测试">
                                    邮件测试
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
    <script src="{{URL::asset('/js/admin/func/contract/detail/index.js')}}"></script>
@endsection