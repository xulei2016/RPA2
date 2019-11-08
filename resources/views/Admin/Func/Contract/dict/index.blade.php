@extends('Admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="panel-body">
            <form class="" id="setting">
                <table class="table table-bordered table-striped table-hover table-base">
                    <tr>
                        <th width="20%" class="text-right">名称</th>
                        <th width="35%" class="text-left">内容</th>
                        <th width="45%" class="text-left">其它</th>
                    </tr>
                    @foreach($dict as $setting)
                        <tr>
                            <td class="text-right">{{$setting['desc']}}</td>
                            <td class="text-left">
                                @if($setting['field'] == 'text')
                                    <input required type="text" class="form-control" name="{{$setting['name']}}" id="{{$setting['name']}}" value="{{$setting['value']}}">
                                @elseif($setting['field'] == "checkbox")
                                    <input type="checkbox" class="form-control" name="" id="" @if('on' == $setting['value']) checked @endif />
                                    <input type="hidden" name="{{$setting['name']}}" id="{{$setting['name']}}" value="{{$setting['value']}}">
                                @endif
                            </td>
                            <td class="text-left text-danger">
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td>
                            <a class="btn btn-success" id="save">提交</a>
                            <a class="btn btn-primary" href="/admin/rpa_contract_detail"  title="返回">
                                返 回
                            </a>
                        </td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>


    <script src="{{URL::asset('/js/admin/func/contract/dict/index.js')}}"></script>

@endsection