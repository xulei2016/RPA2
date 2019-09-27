@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-primary" style="min-height: 700px;padding: 30px 20px">
                    <div class="row">
                        @foreach($list as $k => $v)
                            <div class="col-md-3">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ $v['name'] }}</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body" style="display: block;min-height: 150px;">
                                        {{ $v['desc'] }}
                                    </div>
                                    <div class="card-footer" item-id="{{$v['id']}}">
                                        @if($v['download'])
                                            <button class="btn btn-sm btn-default pull-right download" url="/admin/rpa_download_plugin/{{$v['id']}}" style="margin-left: 6px;">下载</button>
                                        @endif
                                        <button class="btn btn-sm btn-primary pull-right apply">申请</button>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/DownloadPlugin/index.js')}}"></script>
@endsection