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
                            <a class="btn btn-primary btn-sm" href="javascript:history.go(-1);"  title="返回">
                                    返 回
                                </a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">状态</option>
                                        <option value="1">申请中</option>
                                        <option value="2">申请成功</option>
                                        <option value="3">申请失败</option>
                                    </select>
                                </div>
                            @endslot
                        @endcomponent
                            <input type="hidden" name="pid" id="pid" value="{{$pid}}">
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/plugin/apply/index.js')}}"></script>
@endsection