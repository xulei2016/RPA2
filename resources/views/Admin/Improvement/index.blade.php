@extends('admin.layouts.wrapper-content')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">编辑意见</h3>
            </div>
            <div class="box-body pad">
                <form>
                    <div class="form-group">
                        <input class="form-control project" name="project" placeholder="标题:" required>
                    </div>
                    <div class="form-group">
                        <textarea id="editor" name="content" rows="10" cols="80" placeholder="意见描述" required>
                            <blockquote>
                                <h1>意见描述</h1>
                            </blockquote>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary submit"><i class="fa fa-envelope-o"></i> 提交</button>
                    </div>
                </form>
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