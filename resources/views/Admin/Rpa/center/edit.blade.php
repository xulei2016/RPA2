@component('admin.widgets.editForm')    
    @slot('formContent')

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" value="{{ $info->name }}" placeholder="任务名称" required>
            </div>
        </div>
        <div class="form-group">
            <label for="filepath" class="col-sm-2 control-label"><span class="must-tag">*</span>路径</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="filepath" id="filepath" value="{{ $info->filepath }}" placeholder="资源路径" required>
            </div>
        </div>
        <div class="form-group">
            <label for="failtimes" class="col-sm-2 control-label"><span class="must-tag">*</span>失败尝试次数</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="failtimes" id="failtimes" value="{{ $info->failtimes }}" placeholder="失败尝试次数" required>
            </div>
        </div>
        <div class="form-group">
            <label for="timeout" class="col-sm-2 control-label"><span class="must-tag">*</span>任务超时时长</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="timeout" id="timeout" value="{{ $info->timeout }}" placeholder="任务超时时长" required>
            </div>
        </div>
        <div class="form-group">
            <label for="PaS" class="col-sm-2 control-label">服务器</label>
            <div class="col-sm-10">
                <select name="PaS" class="form-control" id="PaS">
                    <option value="主服务器" @if('主服务器' == $info->PaS) selected @endif>主服务器</option>
                    <option value="从服务器" @if('从服务器' == $info->PaS) selected @endif>从服务器</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="isfp" class="col-sm-2 control-label">是否暂用资源</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="isfp" id="isfp" value="1" @if(1 == $info->isfp) checked @endif />
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="bewrite" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="bewrite" id="bewrite" placeholder="任务描述" required> {{ $info->bewrite }} </textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="notice_type" class="col-sm-2 control-label">消息通知</label>
            <div class="col-sm-10">
                <select name="notice_type" class="form-control" id="notice_type">
                    <option value="0" @if(0 == $info->notice_type) selected @endif>不通知</option>
                    <option value="1" @if(1 == $info->notice_type) selected @endif>个人</option>
                    <option value="2" @if(2 == $info->notice_type) selected @endif>分组</option>
                    <option value="3" @if(3 == $info->notice_type) selected @endif>角色</option>
                    <option value="4" @if(4 == $info->notice_type) selected @endif>全体</option>
                </select>
            </div>
        </div>
        <div class="form-group  @if(0 == $info->notice_type || 4 == $info->notice_type) hidden @endif accepter">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10 accepter-content">
                @if($accepters)
                    @foreach($accepters as $accepter)
                    <label>
                        <input type="checkbox" name="noticeAccepter[]" value="{{ $accepter->id }}" 
                        @if(in_array($accepter->id, $info->noticeAccepter)) checked @endif
                        >{{ $accepter->name }}
                    </label>
                    @endforeach
                @else
                    暂无数据！
                @endif
            </div>
        </div>
        <input type="hidden" name="id" id="id" value="{{ $info->id }}">
    @endslot
    @slot('formScript')
    <script src="{{URL::asset('/js/admin/rpa/center/edit.js')}}"></script>
    @endslot
@endcomponent
