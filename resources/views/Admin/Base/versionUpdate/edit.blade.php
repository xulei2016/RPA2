@component('Admin.widgets.editForm')
    @slot('formContent')
        <div class="box-body pad">
            <div class="form-group">
                <select name="type" id="type" class="form-control">
                    @foreach($typeList as $k => $v)
                        @if($info->type == $k)
                            <option selected value="{{$k}}">{{$v}}</option>
                            @else
                            <option value="{{$k}}">{{$v}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="online_time" id="online_time" placeholder="上线时间" value="{{$info->online_time}}">
            </div>
            <div class="form-group">
                <textarea id="editor_desc" class="form-control" name="desc" rows="10" cols="6" placeholder="描述">{{$info->desc}}</textarea>
            </div>
            <div class="form-group">
                <textarea id="editor_content" class="form-control" name="content" rows="10" cols="80" placeholder="详细内容">
                    {{$info->content}}
                </textarea>
            </div>
            <input type="hidden" name="id" id="id" value="{{$info->id}}">
        </div>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/versionUpdate/edit.js')}}"></script>
    @endslot
@endcomponent