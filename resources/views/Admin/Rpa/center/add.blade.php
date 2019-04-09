@component('admin.widgets.addForm')    
    @slot('formContent')

        <div class="form-group">
            <label for="name" class="col-sm-2 control-label"><span class="must-tag">*</span>任务名称</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="name" placeholder="任务名称" required>
            </div>
        </div>
        <div class="form-group">
            <label for="filepath" class="col-sm-2 control-label"><span class="must-tag">*</span>路径</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="filepath" id="filepath" placeholder="资源路径" required>
            </div>
        </div>
        <div class="form-group">
            <label for="failtimes" class="col-sm-2 control-label"><span class="must-tag">*</span>失败尝试次数</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="failtimes" id="failtimes" placeholder="失败尝试次数" required>
            </div>
        </div>
        <div class="form-group">
            <label for="timeout" class="col-sm-2 control-label"><span class="must-tag">*</span>任务超时时长</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="timeout" id="timeout" placeholder="任务超时时长" required>
            </div>
        </div>
        <div class="form-group">
            <label for="PaS" class="col-sm-2 control-label">服务器</label>
            <div class="col-sm-10">
                <select name="PaS" class="form-control" id="PaS">
                    <option value="">全部</option>
                    <option value="主服务器">主服务器</option>
                    <option value="从服务器">从服务器</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="isfp" class="col-sm-2 control-label">是否暂用资源</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="isfp" id="isfp" value="1"/>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="bewrite" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="bewrite" id="bewrite" placeholder="任务描述" required></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="notice_type" class="col-sm-2 control-label">消息通知</label>
            <div class="col-sm-10">
                <select name="notice_type" class="form-control" id="notice_type">
                    <option value="0" selected>不通知</option>
                    <option value="1">个人</option>
                    <option value="2">分组</option>
                    <option value="3">角色</option>
                    <option value="4">全体</option>
                </select>
            </div>
        </div>
        <div class="form-group hidden accepter">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10 accepter-content">
            </div>
        </div>

    @endslot
    @slot('formScript')
    <script src="{{URL::asset('/js/admin/rpa/center/add.js')}}"></script>
    @endslot
@endcomponent
