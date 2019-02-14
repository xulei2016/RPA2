@component('admin.widgets.addForm')    
    @slot('formContent')

        <div class="form-group">
            <label for="api" class="col-sm-2 control-label"><span class="must-tag">*</span>api名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="api" id="api" placeholder="api名称">
            </div>
        </div>

        <div class="form-group">
            <label for="url" class="col-sm-2 control-label"><span class="must-tag">*</span>请求路由</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="url" id="url" placeholder="请求路由">
            </div>
        </div>

        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">请求方式</label>
            <div class="col-sm-10">
                <select name="method" class="form-control" id="method">
                    <option value="POST">POST</option>
                    <option value="GET">GET</option>
                    <option value="PUT">PUT</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="ip" class="col-sm-2 control-label"><span class="must-tag">*</span>黑名单</label>
            <div class="col-sm-10">
                <div class="target_data black">
                    <div class="row">
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
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="ip" class="col-sm-2 control-label"><span class="must-tag">*</span>白名单</label>
            <div class="col-sm-10">
                <div class="target_data white">
                    <div class="row">
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
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="desc" class="col-sm-2 control-label"><span class="must-tag">*</span>接口描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="desc" id="desc" placeholder="接口描述" required></textarea>
            </div>
        </div>
        <input type="hidden" class="form-control" id="black_list" name="black_list">
        <input type="hidden" class="form-control" id="white_list" name="white_list">

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/api/add.js')}}"></script>
    @endslot
@endcomponent