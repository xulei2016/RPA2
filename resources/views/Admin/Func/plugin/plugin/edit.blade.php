@component('Admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="nickname" class="col-sm-12 control-label"><span class="must-tag">*</span>插件名称</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="插件名称" value="{{$plugin->name}}">
            </div>
        </div>


        <div class="form-group row">
            <label for="desc" class="col-sm-12 control-label">描述</label>
            
            <div class="col-sm-12">
            <textarea  id="editor_desc" class="form-control" name="desc" id="desc" placeholder="描述">
                {{$plugin->desc}}
            </textarea>
 
            </div>
        </div>

        <div class="form-group row">
            <label for="type" class="col-sm-12 control-label">状态</label>
            <div class="col-sm-12">
                <div class="switch">
                    <input type="checkbox" name="status" id="status" value="1"  @if(1 == $plugin->status) checked @endif />
                </div>
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{$plugin->id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/plugin/plugin/edit.js')}}"></script>
    @endslot
@endcomponent
