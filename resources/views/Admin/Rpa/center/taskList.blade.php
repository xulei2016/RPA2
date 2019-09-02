@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar', ['operation_show' => false])
                            @slot('listsOperation')
                                @if(auth()->guard('admin')->user()->can('rpa_center'))
                                @endcan
                            @endslot
                            @slot('operation')
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_center/queue">任务队列</a>
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_center">任务管理中心</a>
                            @endslot
                        @endcomponent

                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="{{URL::asset('/js/admin/rpa/center/taskList.js')}}"></script>
@endsection