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
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_cloud_distribution/report">回访报表</a>
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <select name="name" id="name" class="form-control">
                                <option value="" selected>回访人:全部</option>
                                @foreach($list as $name)
                                    @if(session('sys_admin')['realName'] == $name['realName'])
                                        <option value="{{ $name['realName'] }}" selected>{{ $name['realName'] }}</option>
                                    @else
                                        <option value="{{ $name['realName'] }}">{{ $name['realName'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control">
                                <option value="" selected>状态:全部</option>
                                <option value="1">已回访</option>
                                <option value="0">未回访</option>
                                <option value="-1">回访失败</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="customer" placeholder="客户姓名或资金账号">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="videoPeopName" placeholder="视频人姓名">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="checkPeopName" placeholder="审核人姓名">
                        </div>
                        <div  class="col-sm-2">

                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="startTime" placeholder="开始时间">
                        </div>
                        <div style="float:left;">-</div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="endTime" placeholder="结束时间">
                        </div>
                        @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{URL::asset('/js/admin/func/Reviewtables/index.js')}}"></script>
@endsection