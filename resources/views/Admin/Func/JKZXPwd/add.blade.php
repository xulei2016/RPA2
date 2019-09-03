@component('admin.widgets.addForm')    
    @slot('formContent')

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id="name" placeholder="姓名" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="type" class="col-sm-2 control-label"><span class="must-tag">*</span>客户类型</label>
        <div class="col-sm-10">
        <input type="checkbox" class="my-switch" id="type" name="type" value="普通" checked>
        </div>
    </div>

    <div class="form-group row">
        <label for="tel" class="col-sm-2 control-label"><span class="must-tag">*</span>电话号码</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="tel" id="tel" placeholder="电话号码" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="account" class="col-sm-2 control-label"><span class="must-tag">*</span>资金账号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="account" id="account" placeholder="资金账号" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="pwd" class="col-sm-2 control-label"><span class="must-tag">*</span>账户密码</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="pwd" id="pwd" placeholder="账户密码" required>
        </div>
    </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/JKZXPwd/add.js')}}"></script>
    @endslot
@endcomponent