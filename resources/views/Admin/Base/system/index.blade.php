@extends('admin.layouts.wrapper-content')

@section('content')

<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        @foreach($item_group as $v)
            <li class="@if($loop->first) active @endif" ><a href="#{{ $v }}" data-toggle="tab" aria-expanded="false">{{ $v }}</a></li>
        @endforeach
    </ul>
    <div class="tab-content">
      @foreach($item_group as $v)
        <div class="tab-pane @if($loop->first) active @endif" id="{{ $v }}">
        <form class="form-horizontal">
          @foreach($sysconfig as $config)
            @if($config->item_group == $v)
              <div class="form-group">
                <label for="{{$config->item_key}}" class="col-sm-2 control-label">{{$config->label}}</label>
                <div class="col-sm-10">
                    @if('text' == $config->type)
                        <input type="{{$config->type}}" class="form-control" name="{{$config->item_key}}" id="{{$config->item_key}}" value="{{$config->item_value}}" placeholder="{{$config->label}}">
                    @elseif('radio' == $config->type)
                        <input type="radio" name="{{$config->item_key}}" id="{{$config->item_key}}">{{$config->item_value}}
                    @elseif('textarea' == $config->type)
                        <textarea name="{{$config->item_key}}" id="{{$config->item_key}}" cols="100%" rows="6">{{$config->item_value}}</textarea>
                    @endif
                </div>
              </div>
            @endif
          @endforeach
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="button" class="btn btn-primary submit">提交</button>
            </div>
          </div>
        </form>
      </div>
      @endforeach
    </div>
  </div>
<script src="{{URL::asset('/js/admin/base/system/index.js')}}"></script>
@endsection