<!DOCTYPE html>
<html>

{{-- header --}} 
@include('admin.layouts.header')


<body class="hold-transition skin-blue fixed sidebar-mini">
    <div class="wrapper">
        
        {{-- left bar --}} 
        @include('admin.layouts.sidebar')

        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            {{-- top --}}
            @include('admin.layouts.top')

            {{-- wrapper --}} 
            @include('admin.layouts.wrapper')
            
            {{-- wrapper content --}}
            @yield('wrapper-content')
            
        </div>
        
        {{-- footer --}}
        @include('admin.layouts.footer')

        {{-- model --}} 
        @include('admin.layouts.model')


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>

    </div>
    <!-- ./wrapper -->

    {{-- foot script --}}
    @include('admin.layouts.footer-script')
</body>
<script src="{{URL::asset('/include/adminlte/js/demo.js')}}"></script>
</html>