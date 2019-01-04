@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel box box-primary">
        <div class="box-body">

            @component('admin.widgets.toolbar', ['operation_show' => false])
                @slot('listsOperation')
                    @if(auth()->guard('admin')->user()->can('rpa_center'))
                        <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                    @endcan
                @endslot
                @slot('operation')
                    <a class="btn btn-primary btn-sm" href="/admin/rpa_center/taskList">发布任务一览</a>
                    <a class="btn btn-primary btn-sm" href="/admin/rpa_center">任务管理中心</a>
                @endslot
            @endcomponent

            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
            
        </div>
    </div>

<script src="{{URL::asset('/js/admin/rpa/center/queue.js')}}"></script>
@endsection