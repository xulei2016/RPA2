@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel box box-primary">
        <div class="box-body">

            @component('admin.widgets.toolbar', ['operation_show' => false])
                @slot('listsOperation')
                    @if(auth()->guard('admin')->user()->can('rpa_botton'))
                        <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                        <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
                        <li><a href="javascript:void(0)" id="export">导出选中</a></li>
                    @endcan
                @endslot
                @slot('operation')
                    <a class="btn btn-danger btn-sm importExcel" url="/admin/rpa_botton/importExcel" title="批量导入" onclick="operation($(this));">
                        <span class="glyphicon glyphicon-open"></span><span class="hidden-xs">&nbsp;批量导入</span>
                    </a>
                @endslot
            @endcomponent

            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
            
        </div>
    </div>

<script src="{{URL::asset('/js/admin/rpa/botton/index.js')}}"></script>
@endsection