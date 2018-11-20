@extends('admin.layouts.wrapper-content')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">编辑邮件</h3>
                </div>
                <div class="box-body pad">
                    <form>
                        <div class="form-group">
                            <input class="form-control" placeholder="发送人:" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="抄送:">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="主题:" required>
                        </div>
                        <div class="form-group">
                            <textarea id="editor1" name="editor1" rows="10" cols="80">写点什么吧.</textarea>
                        </div>
                        <div class="form-group">
                            <div class="btn btn-default btn-file">
                                <i class="fa fa-paperclip"></i> 附件
                                <input type="file" name="attachment">
                            </div>
                            <p class="help-block">最大. 32MB</p>
                        </div>
                        <div class="form-group">
                            <div class="pull-right">
                                <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> 草稿</button>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> 发送</button>
                            </div>
                            <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> 放弃</button>
                        </div>
                    </form>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="button" class="btn btn-default"><i class="fa fa-pencil"></i> 草稿</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> 发送</button>
                    </div>
                    <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> 放弃</button>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        CKEDITOR.replace('editor1')
    })
</script>

@endsection