@component('admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="api" class="col-sm-2 control-label"><span class="must-tag">*</span>api名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="api" id="api" placeholder="api名称" value="{{$apiip->api}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="url" class="col-sm-2 control-label"><span class="must-tag">*</span>请求路由</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="url" id="url" placeholder="请求路由" value="{{$apiip->url}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="method" class="col-sm-2 control-label">请求方式</label>
            <div class="col-sm-10">
                <select name="method" class="form-control" id="method">
                    <option value="POST" @if('POST' == $apiip->method) selected @endif>POST</option>
                    <option value="GET" @if('GET' == $apiip->method) selected @endif>GET</option>
                    <option value="PUT" @if('PUT' == $apiip->method) selected @endif>PUT</option>
                    <option value="DELETE" @if('DELETE' == $apiip->method) selected @endif>DELETE</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="state" class="col-sm-2 control-label">是否开启</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="state" id="state" value="1" @if(1 == $apiip->state) checked @endif/>
                </div>
            </div>
        </div>

        <div class="form-group row ">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>黑名单</label>
            <div class="col-sm-10">
                <div class="target_data black">
                    @if($apiip->black_list)
                        @foreach($apiip->black_list as $k => $data)
                            <div class="row weblist">
                                <div class="col-xs-7">
                                    <input type="text" class="form-control" id="ip" name="ip" value="{{$k}}" placeholder="例如：127.0.0.0">
                                </div>
                                <div class="col-xs-3">
                                    <input type="text" class="form-control" id="name" name="name" value="{{$data}}" placeholder="姓名">
                                </div>
                                <div class="col-xs-2">
                                @if($loop->first)
                                    <a href="javascript:void(0);" id="add_data" class="btn btn-sm btn-primary">增加</a>
                                @else
                                    <a href="javascript:void(0);" id="del_data" class="btn btn-sm btn-danger">删除</a>
                                @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row weblist">
                            <div class="col-xs-7">
                                <input type="text" class="form-control" id="ip" name="ip" placeholder="例如：172.0.0.0">
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="姓名">
                            </div>
                            <div class="col-xs-2">
                                <a href="javascript:void(0);" class="btn btn-sm btn-primary add_data">增加</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row ">
            <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>白名单</label>
            <div class="col-sm-10">
                <div class="target_data white">
                    @if($apiip->white_list)
                        @foreach($apiip->white_list as $k => $data)
                            <div class="row weblist">
                                <div class="col-xs-7">
                                    <input type="text" class="form-control" id="ip" name="ip" value="{{$k}}" placeholder="例如：127.0.0.0">
                                </div>
                                <div class="col-xs-3">
                                    <input type="text" class="form-control" id="name" name="name" value="{{$data}}" placeholder="姓名">
                                </div>
                                <div class="col-xs-2">
                                @if($loop->first)
                                    <a href="javascript:void(0);" id="add_data" class="btn btn-sm btn-primary">增加</a>
                                @else
                                    <a href="javascript:void(0);" id="del_data" class="btn btn-sm btn-danger">删除</a>
                                @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row weblist">
                            <div class="col-xs-7">
                                <input type="text" class="form-control" id="ip" name="ip" placeholder="例如：172.0.0.0">
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control" id="name" name="name" placeholder="姓名">
                            </div>
                            <div class="col-xs-2">
                                <a href="javascript:void(0);" class="btn btn-sm btn-primary add_data">增加</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>接口描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" placeholder="接口描述" required>{{$apiip->desc}}</textarea>
            </div>
        </div>
        {{ method_field('PATCH')}}
        <input type="hidden" class="form-control" id="id" name="id" value="{{$apiip->id}}">
        <input type="hidden" class="form-control" id="black_list" name="black_list">
        <input type="hidden" class="form-control" id="white_list" name="white_list">

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/api/edit.js')}}"></script>
    @endslot
@endcomponent