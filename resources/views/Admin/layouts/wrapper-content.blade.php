@extends('admin.layouts.index')

@section('wrapper-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">

        
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <h1>
                仪表盘
                <small>控制单元</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">仪表盘</li>
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
