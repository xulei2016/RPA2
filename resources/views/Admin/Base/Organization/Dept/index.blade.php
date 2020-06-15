@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
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
                            <input class="form-control" placeholder="搜索部门" id="searchFile">
                            <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat" onclick="RPA.Alert.howSearch()"><i class="fa fa-question-circle"></i></button>
                            </span>
                        </div>
                        <div class="zTreeDemoBackground left">
                            <ul id="tree" class="ztree"></ul>
                        </div>
                    </div>
                </div>
            </div>
            <div id="detail" class="col-md-8">
{{--       默认card展示 start        --}}
                <div id="initCard" class="">
                    <div class="card card-primary card-outline ">
                        <div class="card-header">
                            <h3 class="card-title">查阅详细</h3>
                            <div class="card-tools">
                                <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                                <a href="#" class="btn btn-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h1>部门管理</h1>

                            <hr />
                            <p>1、请不要随意做删除操作</p>
                        </div>
                    </div>
                </div>
{{--       默认card展示 end        --}}
{{--       node start        --}}
                <div style="display: none" id="nodeCard">


                </div>
{{--       node end       --}}
{{--       person start        --}}
                <div style="display: none" id="personCard">

                </div>
{{--       person end       --}}

            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{URL::asset('/include/zTree_v3/css/metroStyle/metroStyle.css')}}" type="text/css">
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.core.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.excheck.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exhide.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/jquery.ztree.exedit.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('/include/zTree_v3/js/fuzzysearch.js')}}"></script>
    <script src="{{URL::asset('/js/admin/base/organization/dept/index.js')}}"></script>
@endsection