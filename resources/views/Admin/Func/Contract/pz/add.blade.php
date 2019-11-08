@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="jys_id" class="col-sm-12 control-label">交易所</label>
            <div class="col-sm-12">
                <select name="jys_id" id="jys_id" class="form-control">
                    <option value="">未选择</option>
                    @foreach($jys as $v)
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>
        
        <div class="form-group row">
            <label for="name" class="col-sm-12 control-label"><span class="must-tag">*</span>品种名称</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="品种名称">
            </div>
        </div>

        <div class="form-group row">
            <label for="code" class="col-sm-12 control-label"><span class="must-tag">*</span>品种代码</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="code" id="code" placeholder="品种代码">
            </div>
        </div>

        <div class="form-group row">
            <label for="desc" class="col-sm-12 control-label">描述</label>
            <div class="col-sm-12">
                <textarea  id="desc" class="form-control" name="desc" id="desc" placeholder="描述、备注"></textarea>
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/contract/pz/add.js')}}"></script>
    @endslot
@endcomponent
