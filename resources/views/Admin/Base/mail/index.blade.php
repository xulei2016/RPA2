@extends('admin.layouts.wrapper-content')

@section('content')

<div class="row">
    <div class="mail-box col-md-3">
        <a href="/admin/sys_mail/create" title="新增" class="btn btn-primary btn-block margin-bottom">发邮件</a>

        <div class="box box-solid">
            <div class="box-header with-border">
            <h3 class="box-title">Folders</h3>

            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            </div>
            <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @foreach($global as $mail)
                    <li class=" @if ($loop->first) active @endif" data-value="{{ $mail['id'] }}"><a href="#"><i class="fa {{ $mail['icon'] }}"></i> {{ $mail['name'] }}
                    @if($mail['count'])
                    <span class="label label-primary pull-right">{{ $mail['count'] }}</span>
                    @endif
                    </a></li>
                @endforeach
            </ul>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->
        <div class="box box-solid">
            <div class="box-header with-border">
            <h3 class="box-title">Labels</h3>

            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            </div>
            <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Important</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> Promotions</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
            </ul>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-md-9">

        <div class="panel box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">收件箱</h3>
            </div>
            <div class="box-body">
                @component('admin.widgets.toolbar')
                    @slot('listsOperation')
                        <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                        @if(auth()->guard('admin')->user()->can('sys_logs_export'))
                            <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
                            <li><a href="javascript:void(0)" id="export">导出选中</a></li>
                        @endcan
                    @endslot
                    @slot('operation')

                    @endslot
                @endcomponent
        
                @component('admin.widgets.search-group')
                    @slot('searchContent')
                    <label class="control-label col-sm-1" for="title">主题</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="title">
                        </div>
                    @endslot
                @endcomponent
        
                    
                <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
            </div>
        </div>
    </div>
</div>

<script src="{{URL::asset('/js/admin/base/mail/index.js')}}"></script>
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