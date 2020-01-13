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
                                    <select name="jys" id="jys" class="form-control">
                                        <option value="" selected>交易所:未选择</option>
                                        <option value="1">大连交易所</option>
                                        <option value="2">上海交易所</option>
                                        <option value="3">中国金融期货交易所</option>
                                        <option value="4">郑州交易所</option>
                                        <option value="5">能源交易所</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="" selected>来源:(公司或交易所)</option>
                                        <option value="TGSPZBZJ">公司</option>
                                        <option value="TJYSPZBZJ">交易所</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="hydm" placeholder="合约代码">
                                </div>
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/Bzj/index.js')}}"></script>
@endsection