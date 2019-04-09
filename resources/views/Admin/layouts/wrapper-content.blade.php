@extends('admin.layouts.index')

@section('wrapper-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">

        
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @if(session('keepMenu'))
                {{ session('keepMenu')['title'] }}
                <small>{{ session('keepMenu')['unique_name'] }}</small>
            @else
                首页
                <small>仪表盘</small>
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">
                @if(session('keepMenu'))
                    {{ session('keepMenu')['title'] }}
                @else
                    仪表盘
                @endif
            </li>
        </ol>
    </section>
    
        <section class="content">

        {{-- content --}}
        @yield('content')
        
        </section>

    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.0.0
        </div>
        <strong>Copyright &copy; 2017-2018 <a href="">华安futures </a>软件工程部 DESIGN.</strong> All rights reserved.
    </footer>

@endsection
