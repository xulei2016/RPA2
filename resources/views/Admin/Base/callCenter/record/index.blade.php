@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="box-body">
            @component('admin.widgets.toolbar')
                @slot('listsOperation')
                    {{--                    @if(auth()->guard('admin')->user()->can('sys_call_center_manager_export'))--}}
                    <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
                    <li><a href="javascript:void(0)" id="export">导出选中</a></li>
                    {{--                    @endcan--}}
                @endslot
                @slot('operation')

                @endslot
            @endcomponent

            @component('admin.widgets.search-group')
                @slot('searchContent')
                    <label class="control-label col-sm-1" for="manager_id">客服</label>
                    <div class="col-sm-2">
                        <select name="manager_id" id="manager_id">
                            <option value="">未选择</option>
                            @foreach($managers as $manager)
                                <option value="{{$manager->id}}">{{$manager->nickname}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="control-label col-sm-1" for="customer_id">客户</label>
                    <div class="col-sm-2">
                        <select name="customer_id" id="customer_id">
                            <option value="">未选择</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endslot
            @endcomponent


            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/base/callCenter/record/index.js')}}"></script>
@endsection