@extends('admin.layouts.wrapper-content')

@section('content')

    <style>
        .dd { position: relative; display: block; margin: 10px; padding: 0; list-style: none; font-size: 13px; line-height: 20px; }
        .dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
        .dd-list .dd-list { padding-left: 30px; }
        .dd-collapsed .dd-list { display: none; }

        .dd-item,
        .dd-empty,
        .dd-placeholder { display: block; position: relative; margin: 0; padding: 0;}

        .dd-handle {
            display: block;
            margin: 1px 0;
            padding: 8px 10px;
            color: #333;
            text-decoration: none;
            border: 1px solid #ddd;
            background: #fff;
        }
        .dd-handle:hover { color: #2ea8e5; background: #fff; }

        .dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
        .dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
        .dd-item > button[data-action="collapse"]:before { content: '-'; }

        .dd-placeholder { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }

        .dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
        .dd-dragel > .dd-item .dd-handle { margin-top: 0; }
        .dd-dragel .dd-handle {
        -webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
        box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
        }
    </style>

    {{-- content here --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                
                    <div class="card-header">
                
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm tree-ntstable-tree-tools" data-action="expand" title="展开">
                                <i class="fa fa-plus-square-o"></i>&nbsp;展开
                            </button>
                            <button class="btn btn-primary btn-sm tree-ntstable-tree-tools" data-action="collapse" title="收起">
                                <i class="fa fa-minus-square-o"></i>&nbsp;收起
                            </button>
                        </div>
                
                        <div class="btn-group">
                            <button class="btn btn-info btn-sm tree-ntstable-save" title="保存"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;保存</span></button>
                        </div>
                        
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm tree-ntstable-refresh" title="刷新"><i class="fa fa-refresh"></i><span class="hidden-xs">&nbsp;刷新</span></button>
                        </div>
                        
                        <div class="btn-group">
                            <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/sys_menu/create" title="新增" onclick="operation($(this));">
                                <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                            </a>
                        </div>
                
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive no-padding">
                        <div class="dd" id="tree-ntstable">
                            <ol class="dd-list">
                                @foreach($menuList as $menus)
                                    <li class="dd-item" data-id="{{ $menus['id'] }}">
                                        <div class="dd-handle">
                                            <i class="fa fa-bar-chart"></i>&nbsp;<strong>{{ $menus['title'] }}</strong>&nbsp;&nbsp;&nbsp;
                                            <a href="{{ $menus['uri'] }}" class="dd-nodrag">{{ $menus['uri'] }}</a>
                                            <span class="pull-right dd-nodrag">
                                                <a url="/admin/sys_menu/{{ $menus['id'] }}/edit" onclick="operation($(this));"><i class="fa fa-edit"></i></a>
                                                <a href="javascript:void(0);" data-id="{{ $menus['id'] }}" class="tree_branch_delete"><i class="fa fa-trash"></i></a>
                                            </span>
                                        </div>
                                        @if(!empty($menus['child']))
                                            <ol class="dd-list">
                                            @foreach($menus['child'] as $menu)
                                                <li class="dd-item" data-id="{{ $menu['id'] }}">
                                                    <div class="dd-handle">
                                                        <i class="fa fa-users"></i>&nbsp;<strong>{{ $menu['title'] }}</strong>&nbsp;&nbsp;&nbsp;
                                                        <a href="javascript:void(0);" class="dd-nodrag">{{ $menu['uri'] }}</a>
                                                        <span class="pull-right dd-nodrag">
                                                            <a url="/admin/sys_menu/{{ $menu['id'] }}/edit" onclick="operation($(this));"><i class="fa fa-edit"></i></a>
                                                            <a href="javascript:void(0);" data-id="{{ $menu['id'] }}" class="tree_branch_delete"><i class="fa fa-trash"></i></a>
                                                        </span>
                                                    </div>
                                                </li>
                                                @if(!empty($menu['child']))
                                                    <ol class="dd-list">
                                                    @foreach($menu['child'] as $menu1)
                                                        <li class="dd-item" data-id="{{ $menu1['id'] }}">
                                                            <div class="dd-handle">
                                                                <i class="fa fa-users"></i>&nbsp;<strong>{{ $menu1['title'] }}</strong>&nbsp;&nbsp;&nbsp;
                                                                <a href="javascript:void(0);" class="dd-nodrag">{{ $menu1['uri'] }}</a>
                                                                <span class="pull-right dd-nodrag">
                                                                    <a url="/admin/sys_menu/{{ $menu1['id'] }}/edit" onclick="operation($(this));"><i class="fa fa-edit"></i></a>
                                                                    <a href="javascript:void(0);" data-id="{{ $menu1['id'] }}" class="tree_branch_delete"><i class="fa fa-trash"></i></a>
                                                                </span>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    </ol>
                                                @endif
                                            @endforeach
                                            </ol>
                                        @endif
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/include/nestable/jquery.nestable.js')}}"></script>
    <script>
        $(function () {
            $('#tree-ntstable').nestable([]);
    
            $('.tree_branch_delete').click(function() {
                var id = $(this).data('id');
                Swal({
                    title: "确认删除?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "取消",
                    preConfirm: function() {
                        return new Promise(function(resolve) {
                            $.ajax({
                                method: 'post',
                                url: '/admin/sys_menu/' + id,
                                data: {
                                    _method:'delete',
                                    _token:LA.token,
                                },
                                success: function (json) {
                                    if(200 == json.code){
                                        $.pjax.reload('#pjax-container');
                                        toastr.success('删除成功 !');
                                        resolve(json);
                                    }else{
                                        toastr.error(json.info);
                                    }
                                }
                            });
                        });
                    }
                }).then(function(json) {
                    var json = json.value;
                    if (typeof json === 'object') {
                        if (200 == json.code) {
                            Swal(json.info, '', 'success');
                        } else {
                            Swal(json.info, '', 'error');
                        }
                    }
                });
            });
    
            $('.tree-ntstable-save').click(function () {
                var serialize = $('#tree-ntstable').nestable('serialize');
    
                $.post('/admin/sys_menu/order', {
                    _token: LA.token,
                    _order: JSON.stringify(serialize)
                },
                function(json){
                    if(200 == json.code){
                        $.pjax.reload('#pjax-container');
                        toastr.success('保存成功 !');
                    }else{
                        toastr.error(json.info);
                    }
                });
            });
    
            $('.tree-ntstable-refresh').click(function () {
                $.pjax.reload('#pjax-container');
                toastr.success('刷新成功 !');
            });
    
            $('.tree-ntstable-tree-tools').on('click', function(e){
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse') {
                    $('.dd').nestable('collapseAll');
                }
            });
        });
    </script>

@endsection