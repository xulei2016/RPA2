@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">目录</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-card-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body card-primary">
                        <div class="input-group input-group-sm">
                            <input class="form-control" placeholder="搜索流程" id="searchFlow">
                            <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat" onclick="RPA.Alert.howSearch()"><i class="fa fa-question-circle"></i></button>
                            </span>
                        </div>
                        <div class="zTreeDemoBackground left">
                            <ul id="flowTree" class="ztree"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="detail" class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3>流程一览</h3>
                    </div>
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                <li><a class="dropdown-item" href="javascript:void(0)">暂未添加</a></li>
                            @endslot
                            @slot('operation')

                            @endslot


                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="name" placeholder="流程名称" autocomplete="off">
                                </div>
                                <div class="col-sm-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="">全部流程</option>
                                        <option value="complete">已完成</option>
                                        <option value="todo">未完成</option>
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

    <link rel="stylesheet" href="{{URL::asset('/include/zTree_v3/css/metroStyle/metroStyle.css')}}" type="text/css">
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.core.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.excheck.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exhide.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exedit.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/fuzzysearch.js')}}"></script>
    <script src="{{URL::asset('/js/admin/base/flow/mine/index.js')}}"></script>
@endsection