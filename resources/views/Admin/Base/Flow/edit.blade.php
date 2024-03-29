@component('Admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group">
            <label for="title" class="col-sm-2 control-label"><span class="must-tag">*</span>标题</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="title" id="title" value="{{$data['title']}}" placeholder="流程标题">
            </div>
        </div>

        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">分组</label>
            <div class="col-sm-10">
                <select name="groupID" class="form-control" id="groupID">
                    @foreach($groups as $group)
                        <option value ="{{$group['id']}}" @if($group['id'] == $data->groupID) selected @endif>{{$group['name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">选择模板</label>
            <div class="col-sm-10">
                <select name="template_id" class="form-control" id="template_id">
                    @foreach($temps as $temp)
                        <option value ="{{$temp['id']}}" @if($temp['id'] == $data->template_id) selected @endif>{{$temp['template_name']}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="flow_no" class="col-sm-2 control-label"><span class="must-tag">*</span>流程代号</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="flow_no" id="flow_no" value="{{$data['flow_no']}}" placeholder="代号(英文简称)">
            </div>
        </div>

        <div class="form-group">
            <label for="sort" class="col-sm-2 control-label"><span class="must-tag">*</span>排序</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="sort" id="sort" value="{{$data['sort']}}" placeholder="排序">
            </div>
        </div>

        <div class="form-group">
            <label for="description" class="col-sm-2 control-label">描述</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="description" id="description" value="{{$data['description']}}" placeholder="描述">
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{ $data->id }}">

    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/flow/edit.js')}}"></script>
    @endslot
@endcomponent