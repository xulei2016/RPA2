@extends('admin.layouts.wrapper-content')

@section('content')

<div class="row">
    <div class="mail-box col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <a href="/admin/sys_mail/create" title="新增" class="btn btn-primary btn-block margin-bottom">发邮件</a>
            </div>
            <div class="card-body">
                <div class="card card-solid">
                    <div class="card-header with-border">
                        <h3 class="card-title">Folders</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body no-padding">
                        <ul class="nav nav-pills nav-stacked mail-type">
                            @foreach($global as $mail)
                                <li class=" @if ($loop->first) active @endif" data-value="{{ $mail['id'] }}"><a href="#"><i class="fa {{ $mail['icon'] }}"></i> {{ $mail['desc'] }}
                                        @if($mail['count'])
                                            <span class="label label-primary pull-right">{{ $mail['count'] }}</span>
                                        @endif
                                    </a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3>收件箱</h3>
            </div>
            <div class="card-body">
                @component('admin.widgets.toolbar')
                    @slot('listsOperation')
                        <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                        @if(auth()->guard('admin')->user()->can('sys_logs_export'))
                            <!-- <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li> -->
                            <!-- <li><a href="javascript:void(0)" id="export">导出选中</a></li> -->
                        @endcan
                    @endslot
                    @slot('operation')

                    @endslot
                @endcomponent
                <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
            </div>
        </div>
    </div>
</div>

<script src="{{URL::asset('/js/admin/base/mail/index.js')}}"></script>

@endsection