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
                            @if(auth()->guard('admin')->user()->can('rpa_customer_add'))
                                
                            @endif
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control">
                                <option value="" selected>状态:全部</option>
                                <option value="0">未处理</option>
                                <option value="1">已审核</option>
                                <option value="2">已复核</option>
                                <option value="3">rpa状态1</option>
                                <option value="4">rpa状态2</option>
                                <option value="5">rpa状态3</option>
                                <option value="6">rpa状态4</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="zjzh" placeholder="资金账号">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="startTime" placeholder="开始时间">
                        </div>
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

    <script src="{{URL::asset('/js/admin/func/Recognition/index.js')}}"></script>
@endsection