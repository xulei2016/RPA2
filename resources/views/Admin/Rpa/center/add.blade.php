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
            <label for="failtimes" class="col-sm-2 control-label">失败尝试次数</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="failtimes" id="failtimes" placeholder="失败尝试次数">
            </div>
        </div>
        <div class="form-group">
            <label for="timeout" class="col-sm-2 control-label">任务超时时长</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="timeout" id="timeout" placeholder="任务超时时长">
            </div>
        </div>
        <div class="form-group">
            <label for="isfp" class="col-sm-2 control-label">是否暂用资源</label>
            <div class="col-sm-10">
                <label><input type="radio" class="form-control minimal icheck" name="isfp" value="1" checked>是</label>
                <label><input type="radio" class="form-control minimal icheck" name="isfp" value="0">否</label>
            </div>
        </div>
        <div class="form-group">
            <label for="bewrite" class="col-sm-2 control-label"><span class="must-tag">*</span>任务描述</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="bewrite" id="bewrite" placeholder="任务描述" required></textarea>
            </div>
        </div>

    @endslot
@endcomponent

<script>
    //添加
    function add(e){
        RPA.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_center',
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };
</script>