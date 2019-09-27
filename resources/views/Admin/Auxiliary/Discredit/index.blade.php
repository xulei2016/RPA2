@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid" id="discredit">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form class="form-horizontal">
                            <div class="container">
                                <div class="row form-group">
                                    <label class="control-label col-sm-2" for="name">客户姓名:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="name" id="name" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="control-label col-sm-2" for="name">身份证号:</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" name="idCard" id="idCard" type="text">
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col-sm-10 text-center">
                                        <button type="button" class="btn btn-warning">重置</button>
                                        <button type="button" class="btn btn-success" id="save">查询</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>证券失信</th>
                                    <th>期货失信</th>
                                    <th>恒生黑名单</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td id="zq"></td>
                                    <td id="qh"></td>
                                    <td id="hs"></td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/Auxiliary/Discredit/index.js')}}"></script>
@endsection