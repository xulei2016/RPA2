@component('Admin.widgets.addForm')
    @slot('formContent')

        <div class="form-group">
            <label for="keyword" class="col-sm-2 control-label"><span class="must-tag">*</span>关键词</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="关键词(可以有多个,用英文逗号分开)">
            </div>
        </div>

        <div class="form-group">
            <label for="content" class="col-sm-2 control-label"><span class="must-tag">*</span>内容</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="content" id="content" placeholder="内容">
            </div>
        </div>
        <div class="form-group">
            <label for="method" class="col-sm-2 control-label">分组</label>
            <div class="col-sm-10">
                <select name="group_id" class="form-control" id="group_id">
                    @foreach($groups as $group)
                        <option value ="{{ $group['id'] }}">{{ $group['group'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="sort" class="col-sm-2 control-label">排序</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="sort" id="sort" placeholder="排序" value="99">
            </div>
        </div>

        <div class="form-group">
            <label for="answer" class="col-sm-2 control-label"><span class="must-tag">*</span>回答</label>
            <div class="col-sm-10">
                <textarea  class="form-control" name="answer" id="answer" placeholder="回答"></textarea>
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/callCenter/template/add.js')}}"></script>
    @endslot
@endcomponent