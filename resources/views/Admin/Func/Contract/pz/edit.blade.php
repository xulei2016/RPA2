@component('Admin.widgets.editForm')
    @slot('formContent')


        <div class="form-group row">
            <label for="jys_id" class="col-sm-12 control-label">交易所</label>
            <div class="col-sm-12">
                <select name="jys_id" id="jys_id" class="form-control">
                    @foreach($jys as $v)
                        @if($info->jys_id == $v->id)
                            <option selected value="{{$v->id}}">{{$v->name}}</option>
                        @else
                            <option value="{{$v->id}}">{{$v->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>

        </div>

        <div class="form-group row">
            <label for="name" class="col-sm-12 control-label"><span class="must-tag">*</span>品种名称</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="品种名称" value="{{$info->name}}">
            </div>
        </div>

        <div class="form-group row">
            <label for="code" class="col-sm-12 control-label"><span class="must-tag">*</span>品种代码</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="code" id="code" placeholder="品种代码" value="{{$info->code}}">
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
        <script src="{{URL::asset('/js/admin/func/contract/pz/edit.js')}}"></script>
    @endslot
@endcomponent
