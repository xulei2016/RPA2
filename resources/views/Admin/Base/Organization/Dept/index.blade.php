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
{{--       node start        --}}
{{--       person start        --}}
                <div style="display: none" id="personCard">
                    <div class="card card-primary card-outline" style="min-height: 700px;">
                        <div class="card-header">
                            <div>员工详细信息</div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="50%">属性</th>
                                        <th width="50%">值</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>用户名</td>
                                        <td id="item_name">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>真实姓名</td>
                                        <td id="item_realName">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>状态</td>
                                        <td id="item_statusName">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>部门</td>
                                        <td id="item_department">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>岗位</td>
                                        <td id="item_post">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>角色</td>
                                        <td id="item_roleLists">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>性别</td>
                                        <td id="item_gender">男</td>
                                    </tr>
                                    <tr>
                                        <td>手机号</td>
                                        <td id="item_phone">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>邮箱</td>
                                        <td id="item_email">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>地址</td>
                                        <td id="item_address">暂无</td>
                                    </tr>
                                    <tr>
                                        <td>操作</td>
                                        <td id="item_operation">
                                            <button class="btn btn-primary btn-sm edit_user">编辑</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
{{--       person start        --}}

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