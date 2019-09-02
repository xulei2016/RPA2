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
                        <input class="form-control project" name="title" placeholder="标题" required>
                    </div>
                    <div class="form-group">
                        <textarea id="editor" name="content" rows="10" cols="80" placeholder="意见描述" required>
                            <blockquote>
                                <h1>意见描述</h1>
                            </blockquote>
                        </textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary submit"><i class="fa fa-envelope-o"></i> 提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>
<script src="{{URL::asset('/js/admin/base/improvement/add.js')}}"></script>

@endsection