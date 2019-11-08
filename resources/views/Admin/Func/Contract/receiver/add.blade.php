@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="name" class="col-sm-12 control-label"><span class="must-tag">*</span>姓名</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="name" id="name" placeholder="用户姓名">
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-sm-12 control-label"><span class="must-tag">*</span>邮箱</label>
            <div class="col-sm-12">
                <input type="text" class="form-control" name="email" id="email" placeholder="用户邮箱">
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
        <script src="{{URL::asset('/js/admin/func/contract/receiver/add.js')}}"></script>
    @endslot
@endcomponent
