@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="dept_id" class="col-sm-2 control-label"><span class="must-tag">*</span>所属部门</label>
            <div class="col-sm-10">
                <select name="dept_id" id="dept_id" class="form-control">
                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>岗位名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" placeholder="岗位名称">
            </div>
        </div>

        <div class="form-group row">
            <label for="rank" class="col-sm-2 control-label"><span class="must-tag">*</span>rank</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="rank" id="rank" placeholder="rank">
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/plugin/plugin/add.js')}}"></script>
    @endslot
@endcomponent