<!DOCTYPE html>
<html>

{{-- header --}} 
@include('admin.layouts.header')


<body class="hold-transition skin-blue fixed sidebar-mini">
    <div class="wrapper">
        
        {{-- left bar --}} 
        @include('admin.layouts.sidebar')

        {{-- top --}}
        @include('admin.layouts.top')
        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            {{--<div class="tags-view-container">--}}
                {{--<div class="tags-warp">--}}
                    {{--<div class="scrop"></div>--}}
                {{--</div>--}}
            {{--</div>--}}

            @include('admin.layouts.alerts')
            
            {{-- wrapper content --}}
            @yield('wrapper-content')

            {{-- model --}}
            @include('admin.layouts.model')
            
        </div>
        
        {{-- footer --}}
        @include('admin.layouts.footer')

        {{-- drawerPanel --}}
        @include('admin.layouts.drawerPanel')


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>

    </div>
    <!-- ./wrapper -->

    {{-- foot script --}}
    @include('admin.layouts.footer-script')
</body>
<script src="{{URL::asset('/js/admin/skin.js')}}"></script>
</html>