@component('Admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="method" class="col-sm-2 control-label">所属插件</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" value="{{$plugin->name}}" disabled="disabled">
                <input type="hidden" class="form-control" name="pid"  id="pid"  value="{{$pluginVersion->pid}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="nickname" class="col-sm-2 control-label"><span class="must-tag">*</span>版本号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="version"  id="version" placeholder="版本号" value="{{$pluginVersion->version}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="desc" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="desc" id="desc" placeholder="描述" value="{{$pluginVersion->desc}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="" class="col-sm-2">关联文档</label>
            <div class="col-sm-9">
                <select class="form-control" name="doc_id" id="doc_id" readonly>
                    <option value="{{$pluginVersion->doc_id??''}}" >{{$pluginVersion->doc_id?$pluginVersion->docName:'未选择'}}</option>
                </select>
            </div>
            <div class="col-sm-1">
                <a class="btn btn-primary searchDoc"><i class="fa fa-search"></i></a>
            </div>
        </div>

        <div class="form-group row">
            <label for="type" class="col-sm-2 control-label">状态</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="status" id="status" value="1"  @if(1 == $plugin->status) checked @endif />
                </div>
            </div>
        </div>

        <div class="form-group row" style="margin-left: 30px;">
            <div class="btn btn-default btn-file">
                <i class="fa fa-paperclip"></i> <span>{{$pluginVersion->show_name}}</span>
                <input type="file" id="zip" name="zip" multiple>
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{$pluginVersion->id}}">
        <input type="hidden" name="url" id="url" value="{{$pluginVersion->url}}">
        <input type="hidden" name="show_name" id="show_name" value="{{$pluginVersion->show_name}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/plugin/version/edit.js')}}"></script>
    @endslot
@endcomponent

