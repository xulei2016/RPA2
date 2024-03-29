@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body pad">
                    <form>
                        <div class="form-group">
                            <label for="mode">
                                <select name="mode" id="mode" class="form-control">
                                    @foreach($object as $v)
                                        <option value="{{$v->id}}">{{$v->desc}}</option>
                                    @endforeach
                                </select>
                            </label>
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
                            <input class="form-control title" name="title" placeholder="标题:">
                        </div>
                        <div class="form-group">
                            <textarea id="editor" name="content" rows="10" cols="80" placeholder="写点什么吧."></textarea>
                        </div>
                    </form>
                </div>
                <div class="box-footer">
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary submit"> 发送</button>
                        <button type="reset" class="btn btn-default reset"><i class="fa fa-times"></i> 放弃</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>
    <script src="{{URL::asset('/js/admin/base/message/send.js')}}"></script>

@endsection