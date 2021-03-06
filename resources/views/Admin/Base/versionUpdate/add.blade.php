@component('Admin.widgets.addForm')
    @slot('formContent')
        <div class="box-body pad">
            <div class="form-group">
                <label for="">类型选择:</label>
                <select name="type" id="type" class="form-control">
                    <option value="">类型选择</option>
                    @foreach($typeList as $k => $v)
                        <option value="{{$k}}">{{$v}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="online_time">上线时间</label>
                <input type="text" class="form-control" name="online_time" id="online_time" placeholder="上线时间" value="">
            </div>
            <div class="form-group">
                <label for="version_number">版本号(当前版本: <span class="text-red">{{ $versionNumber->item_value }}</span>, 不填则小版本自动加1)</label>
                <input type="text" class="form-control" name="version_number" id="version_number" placeholder="版本号" value="">
            </div>
            <div class="form-group">
                <label for="">描述:</label>
                <textarea id="editor_desc" class="form-control" name="desc" rows="10" cols="6" placeholder="描述">
                </textarea>
            </div>
            <div class="form-group">
                <label for="">详细内容:</label>
                <textarea id="editor_content" class="form-control" name="content" rows="10" cols="80" placeholder="详细内容">
                </textarea>
            </div>
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/versionUpdate/add.js')}}"></script>
    @endslot
@endcomponent
