@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="method" class="col-sm-2 control-label">所属插件</label>
            <div class="col-sm-10">
                <select name="pid" class="form-control" id="select2-menu">
                    @foreach($plugins as $plugin)
                        <option value ="{{ $plugin['id'] }}">{{ $plugin['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="nickname" class="col-sm-2 control-label"><span class="must-tag">*</span>版本号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="version"  id="version" placeholder="版本号">
            </div>
        </div>

        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="desc" id="desc" placeholder="描述">
            </div>
        </div>

        <div class="form-group row">
            <label for="" class="col-sm-2">关联文档</label>
            <div class="col-sm-9">
                <select class="form-control" name="doc_id" id="doc_id" readonly>
                    <option value="" >未选择</option>
                </select>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-primary searchDoc"><i class="fa fa-search"></i></a>
            </div>
        </div>

        <div class="form-group row" style="margin-left: 30px;">
            <div class="btn btn-default btn-file">
                <i class="fa fa-paperclip"></i> <span>上传附件</span>
                <input type="file" id="zip" name="zip" multiple>
            </div>
        </div>

        <input type="hidden" name="url" id="url">
        <input type="hidden" name="show_name" id="show_name">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/plugin/version/add.js')}}"></script>
    @endslot
@endcomponent
