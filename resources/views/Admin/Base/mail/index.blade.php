@extends('admin.layouts.wrapper-content')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">编辑邮件</h3>
            </div>
            <div class="box-body pad">
                <form>
                    <div class="form-group">
                        <input class="form-control to" name="to" placeholder="发送人:" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control cc" name="cc" placeholder="抄送:">
                    </div>
                    <div class="form-group">
                        <input class="form-control project" name="project" placeholder="主题:" required>
                    </div>
                    <div class="form-group">
                        <textarea id="editor" name="editor" rows="10" cols="80" placeholder="写点什么吧."></textarea>
                    </div>
                    <div class="form-group">
                        <div class="btn btn-default btn-file">
                            <i class="fa fa-paperclip"></i> 附件
                            <input type="file" name="attachment">
                        </div>
                        <p class="help-block">最大. 32MB</p>
                    </div>
                </form>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    <button type="button" class="btn btn-default draft"><i class="fa fa-pencil"></i> 草稿</button>
                    <button type="submit" class="btn btn-primary submit"><i class="fa fa-envelope-o"></i> 发送</button>
                </div>
                <button type="reset" class="btn btn-default reset"><i class="fa fa-times"></i> 放弃</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        //初始化
        function init(){
            bindEvent();
            CKEDITOR.replace('editor');
        }

        //绑定事件
        function bindEvent(){
            //发送事件
            $('#pjax-container section.content button.submit').click(function(){
                var to = $('#pjax-container section.content form input.to').val();
                var project = $('#pjax-container section.content form input.project').val();
                if(!to || !project){
                    return swal('Oops...', '请完善发送信息！！', 'warning');
                }
                add($('#pjax-container section.content form'));
            });
            //草稿事件
            $('#pjax-container section.content button.draft').click(function(){

            });
            //重置事件
            $('#pjax-container section.content button.reset').click(function(){
                $('#pjax-container section.content form')[0].reset();
            });
        }
            
        //添加
        function add(e){
            RPA.ajaxSubmit(e, FormOptions);
        }
        
        //提交信息的表单配置
        var FormOptions={
            url:'/admin/sys_mail',
            success:function(json, xml){
                if(200 == json.code){
                    toastr.success('操作成功！');
                }else{
                    toastr.error(json.info);
                }
            },
            error:RPA.errorReponse
        };

        init();
    })
</script>

@endsection