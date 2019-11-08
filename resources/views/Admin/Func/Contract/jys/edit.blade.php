@component('Admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="name" class="col-sm-12 control-label"><span class="must-tag">*</span>名称</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="交易所名称" value="{{$info->name}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="code" class="col-sm-12 control-label"><span class="must-tag">*</span>交易所代码</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="code" id="code" placeholder="恒生数据库代码" value="{{$info->code}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="desc" class="col-sm-12 control-label">描述</label>
            <div class="col-sm-12">
                <textarea  id="desc" class="form-control" name="desc" id="desc" placeholder="描述、备注">{{$info->desc}}</textarea>
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{$info->id}}" />
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/contract/jys/edit.js')}}"></script>
    @endslot
@endcomponent
