@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid" id="fxq">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <div class="container">
                                <div class="row form-group">
                                    <label class="control-label col-sm-2" for="customernum">资金账号:</label>
                                    <div class="col-sm-6">
                                        <input class="form-control" id="customernum" type="text">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="button" class="btn btn-success" id="save">查询</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th width="10%">姓名</th>
                                    <td class="model0"></td>
                                </tr>
                                <tr>
                                    <th width="10%">模板1</th>
                                    <td class="model1"></td>
                                </tr>
                                <tr>
                                    <th width="10%">模板2</th>
                                    <td class="model2"></td>
                                </tr>
                                <tr>
                                    <th width="10%">模板3</th>
                                    <td class="model3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/Auxiliary/Fxq/index.js')}}"></script>
@endsection