@component('admin.widgets.addForm')    
    @slot('formContent')

    <div class="form-group row">
        <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>姓名</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="name" id="name" placeholder="姓名" value="{{ $info->name }}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="type" class="col-sm-2 control-label"><span class="must-tag">*</span>客户类型</label>
        <div class="col-sm-10">
        <input type="checkbox" class="my-switch" id="type" name="type" value="普通" readonly @if(trim($info->type) =='普通') checked @endif>
        </div>
    </div>

    <div class="form-group row">
        <label for="tel" class="col-sm-2 control-label"><span class="must-tag">*</span>电话号码</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="tel" id="tel" placeholder="电话号码" value="{{ $info->tel }}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="account" class="col-sm-2 control-label"><span class="must-tag">*</span>资金账号</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="account" id="account" placeholder="资金账号" value="{{ $info->account }}" readonly>
        </div>
    </div>

    <div class="form-group row">
        <label for="pwd" class="col-sm-2 control-label"><span class="must-tag">*</span>账户密码</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="pwd" id="pwd" placeholder="账户密码" value="{{ $info->pwd }}" readonly>
        </div>
    </div>

    @if($re)
    <div class="form-group row">
        <label for="lastcontent" class="col-sm-2 control-label"><span class="must-tag">*</span>上次发送内容</label>
        <div class="col-sm-10">
            <textarea type="text" rows="4" class="form-control" name="lastcontent" id="lastcontent" placeholder="短信内容" readonly>{{ $info->lastcontent }}</textarea>
        </div>
    </div>
    @endif
    <div class="form-group row">
        <label for="content" class="col-sm-2 control-label"><span class="must-tag">*</span>短信内容(可修改)</label>
        <div class="col-sm-10">
            <textarea type="text" rows="4" class="form-control" name="content" id="content" placeholder="短信内容">{{ $info->content }}</textarea>
        </div>
    </div>
    <input type="hidden" id="id" name="id" value="{{ $info->id }}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/Func/JKZXPwd/send.js')}}"></script>
    @endslot
@endcomponent