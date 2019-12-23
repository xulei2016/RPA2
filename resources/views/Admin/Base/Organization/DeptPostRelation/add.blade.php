@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="dept_id" class="col-sm-2 control-label"><span class="must-tag">*</span>所属部门</label>
            <div class="col-sm-10">
                <select name="dept_id" id="dept_id" class="form-control">
                    @if($dept)
                        <option value="{{$dept->id}}">{{$dept->name}}</option>
                    @else
                        <option value="">未选择</option>
                        @foreach($deptList as $v)
                            <option value="{{$v->id}}">{{$v->name}}</option>
                        @endforeach
                    @endif

                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="post_id" class="col-sm-2 control-label"><span class="must-tag">*</span>岗位</label>
            <div class="col-sm-10">
                <select name="post_id" id="post_id" class="form-control">
                    <option value="">未选择</option>
                    @foreach($postList as $v)
                        <option value="{{$v->id}}">{{$v->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="fullname" class="col-sm-2 control-label"><span class="must-tag">*</span>岗位别名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="fullname" name="fullname">
            </div>
        </div>

        <div class="form-group row">
            <label for="duty" class="col-sm-2 control-label"> 岗位职责</label>
            <div class="col-sm-10">
                <textarea name="duty" id="duty" class="form-control"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="qualification" class="col-sm-2 control-label"> 任职资格</label>
            <div class="col-sm-10">
                <textarea name="qualification" id="qualification" class="form-control" ></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label for="remark" class="col-sm-2 control-label"> 备注</label>
            <div class="col-sm-10">
                <textarea name="remark" id="remark" class="form-control"></textarea>
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('js/admin/base/organization/deptPostRelation/add.js')}}"></script>
    @endslot
@endcomponent