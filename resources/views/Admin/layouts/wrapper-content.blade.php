@extends('admin.layouts.index')

@section('wrapper-content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="pjax-container">

        {{-- content --}}
        @yield('content')

    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.0.0
        </div>
        <strong>Copyright &copy; 2017-2018 <a href="">华安futures </a>软件工程部 DESIGN.</strong> All rights reserved.
    </footer>

@endsection
