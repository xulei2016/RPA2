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
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')

                        <div class="col-sm-2">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected>平台:全部</option>
                                <option value="中正云">中正云</option>
                                <option value="优信">优信</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="phone" placeholder="手机号">
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

    <script src="{{URL::asset('/js/admin/base/message/sms_list.js')}}"></script>
@endsection