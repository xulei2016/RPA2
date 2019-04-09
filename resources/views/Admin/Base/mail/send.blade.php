@extends('admin.layouts.wrapper-content')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">编辑邮件</h3>
            </div>
            <div class="box-body pad">
                <form enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="mode">
                                <select name="mode" id="mode" class="form-control">
                                    @foreach($object as $v)
                                        <option value="{{$v->id}}">{{$v->desc}}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="form-group hidden accepter">
                        <label for="content">
                            <div class="accepter-content"></div>
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="type">
                            <select name="type" id="type" class="form-control">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="form-group">
                        <input class="form-control project" name="project" placeholder="主题:" required>
                    </div>
                    <div class="form-group">
                        <textarea id="editor" name="editor" rows="10" cols="80" placeholder="写点什么吧."></textarea>
                    </div>
                    <div class="form-group">
                        <div class="btn btn-default btn-file">
                            <i class="fa fa-paperclip"></i> <span>上传附件</span>
                            <input type="file" id="attachment" name="attachment">
                        </div>
                        <p class="help-block">最大. 10MB</p>
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
<script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>
<script src="{{URL::asset('/js/admin/base/mail/send.js')}}"></script>

@endsection