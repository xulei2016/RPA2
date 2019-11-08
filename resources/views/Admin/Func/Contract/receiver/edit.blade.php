@component('Admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="name" class="col-sm-12 control-label"><span class="must-tag">*</span>姓名</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="用户姓名" value="{{$info->name}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-sm-12 control-label"><span class="must-tag">*</span>邮箱</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="email" id="email" placeholder="用户邮箱" value="{{$info->email}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="desc" class="col-sm-12 control-label">描述</label>
            <div class="col-sm-12">
                <textarea  id="desc" class="form-control" name="desc" id="desc" placeholder="描述、备注">{{$info->desc}}</textarea>
            </div>
        </div>

        <div class="form-group row">
            <label for="status" class="col-sm-12 control-label">状态</label>
            <div class="switch">
                <input type="checkbox" name="status" id="status" value="1"  @if(1 == $info->status) checked @endif />
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{$info->id}}" />
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/contract/receiver/edit.js')}}"></script>
    @endslot
@endcomponent
