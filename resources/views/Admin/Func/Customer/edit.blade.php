@component('admin.widgets.editForm')    
    @slot('formContent')

    <div class="form-group row">
        <label for="yyb" class="col-sm-2 control-label"><span class="must-tag">*</span>营业部名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{$customer->yybName}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="fundsNum" class="col-sm-2 control-label"><span class="must-tag">*</span>资金账号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{$customer->fundsNum}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>客户姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{$customer->name}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="idCard" class="col-sm-2 control-label"><span class="must-tag">*</span>身份证号码</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{$customer->idCard}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="customerNum" class="col-sm-2 control-label">客户经理号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{$customer->customerNum}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="jjr" class="col-sm-2 control-label">居间人编号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" value="{{$customer->jjr}}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="message" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <textarea type="text" class="form-control" name="message" id="message" placeholder="备注">{{$customer->message}}</textarea>
        </div>
    </div>
    <input type="hidden" id='id' name='id' value="{{$customer->id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/Customer/edit.js')}}"></script>
    @endslot
@endcomponent