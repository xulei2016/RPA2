@component('admin.widgets.addForm')    
    @slot('formContent')

    <div class="form-group row">
        <label for="yyb" class="col-sm-2 control-label"><span class="must-tag">*</span>营业部名称</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="yyb" id="yyb" placeholder="营业部名称" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="fundsNum" class="col-sm-2 control-label"><span class="must-tag">*</span>资金账号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="fundsNum" id="fundsNum" placeholder="资金账号" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>客户姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id="name" placeholder="客户姓名" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="idCard" class="col-sm-2 control-label"><span class="must-tag">*</span>身份证号码</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="idCard" id="idCard" placeholder="身份证号码" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="customerNum" class="col-sm-2 control-label">客户经理号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="customerNum" id="customerNum" placeholder="客户经理号">
        </div>
    </div>

    <div class="form-group row">
        <label for="jjrNum" class="col-sm-2 control-label">居间人编号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="jjr" id="jjrNum" placeholder="居间人编号">
        </div>
    </div>

    <div class="form-group row">
        <label for="special" class="col-sm-2 control-label">特殊开户</label>
        <div class="col-sm-10">
            <label><input type="checkbox" class="select-single" name="special[]" value="1">仅账户激活</label>
            <label><input type="checkbox" class="select-single" name="special[]" value="2">仅账户更新</label>
            <label><input type="checkbox" class="select-single" name="special[]" value="3">仅二次金融</label>
            <label><input type="checkbox" class="select-single" name="special[]" value="4">仅二次能源</label>
        </div>
    </div>

    <div class="form-group row">
        <label for="message" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <textarea type="text" class="form-control" name="message" id="message" placeholder="备注"></textarea>
        </div>
    </div>

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/Customer/add.js')}}"></script>
    @endslot
@endcomponent