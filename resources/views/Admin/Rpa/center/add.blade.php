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
            <label for="isfp" class="col-sm-2 control-label">是否暂用资源</label>
            <div class="col-sm-10">
                <div class="switch">
                    <input type="checkbox" name="isfp" id="isfp" value="1" checked />
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
@endcomponent

<script>
$(function(){
    $('#modal form .switch input#isfp').bootstrapSwitch({onText:"是", offText:"否"});
    $('#modal form .switch input#type').bootstrapSwitch({onText:"启用", offText:"禁用"});

    $('#modal form select#notice_type').on('change', function(){
        mesNotice($(this));
    });

    //消息通知方式
    function mesNotice(e){
        let val = $(e).val();
        if(val && 4 != val){
            showAccepter(val);
        }else{
            $(e).parents('div.form-group').next().addClass('hidden').find('.accepter-content').html('');
        }
    }

    //查询通知人群
    function showAccepter(val){
        $.post('/admin/rpa_center/getAccepter',{param: val}, function(json){
            if(200 == json.code){
                let data = json.data;
                let _html = '';
                if(data.length > 0){
                    for(let _item of data){
                        _html += ' <label><input type="checkbox" name="noticeAccepter[]" value="'+ _item.id +'">'+_item.name+'</label> '
                    }
                }else{
                    _html += '暂无数据！';
                }
                $('#modal form .accepter .accepter-content').html(_html);
                $('#modal form .accepter').removeClass('hidden');
                $('#modal .accepter input').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue',
                });
                return;
            }
            swal('Oops...', '获取资源数据失败！', 'error');
        });
    }
});

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