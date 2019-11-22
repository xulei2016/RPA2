@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group">
            <label for="title" class="col-sm-2 control-label"><span class="must-tag">*</span>标题</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="title" id="title" placeholder="流程标题">
            </div>
        </div>

        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">分组</label>
            <div class="col-sm-10">
                <select name="groupID" class="form-control" id="groupID">
                    {{-- @foreach($groups as $group) --}}
                        <option value ="1">金融科技部</option>
                    {{-- @endforeach --}}
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="sort" class="col-sm-2 control-label"><span class="must-tag">*</span>排序</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="sort" id="sort" placeholder="排序">
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="description" id="description" placeholder="描述">
            </div>
        </div>

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/flow/add.js')}}"></script>
    @endslot
@endcomponent