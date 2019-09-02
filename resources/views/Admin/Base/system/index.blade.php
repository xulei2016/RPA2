@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-body">
            <div class="nav-tabs-custom">
                <ul class="nav nav-pills">
                    @foreach($item_group as $v)
                        <li class="nav-item"><a class="nav-link @if($loop->first) active @endif" href="#{{ $v }}" data-toggle="tab" aria-expanded="false">{{ $v }}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content">
                  @foreach($item_group as $v)
                    <div class="tab-pane @if($loop->first) active @endif" id="{{ $v }}">
                        <form class="form-horizontal" id="sys_config">
                        <table class="table table-bordered table-striped table-hover table-base">
                            <tr>
                                <th width="15%" class="text-right">名称</th>
                                <th width="30%" class="text-left">内容</th>
                                <th width="40%" class="text-left">其它</th>
                            </tr>

                            @foreach($sysconfig as $config)
                                <tr>
                                @if($config->item_group == $v)
                                    <td class="text-right">{{$config->label}}</td>
                                    <td class="text-left">
                                        @if('text' == $config->type)
                                            <input required type="{{$config->type}}" class="form-control" name="{{$config->item_key}}" id="{{$config->item_key}}" value="{{$config->item_value}}" placeholder="{{$config->label}}">
                                        @elseif('radio' == $config->type)
                                            <input required type="radio" name="{{$config->item_key}}" id="{{$config->item_key}}">{{$config->item_value}}
                                        @elseif('textarea' == $config->type)
                                            <textarea required name="{{$config->item_key}}" id="{{$config->item_key}}" cols="100%" rows="6">{{$config->item_value}}</textarea>
                                        @endif
                                    </td>
                                    <td></td>
                                @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td><button type="button" class="btn btn-primary submit">提交</button></td>
                                <td></td>
                            </tr>
                        </table>
                    </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
<script src="{{URL::asset('/js/admin/base/system/index.js')}}"></script>
@endsection