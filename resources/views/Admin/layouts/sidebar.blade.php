
    {{-- @include('admin.inner.top') --}}

    <div class="sidebar">

        <!-- sidebar -->
        <div class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-fold">

                    <!-- Branding Image -->
                    <a class="sidebar-brand" href="{{ url('/admin') }}">
                        <img src="{{ session('sys_info')['config']['logo'] }}">
                        <span>{{ session('sys_info')['config']['site_title'] }}</span>
                    </a>
                    <!-- /.Branding Image -->

                </div>

                <!-- menu -->
                @each('admin.widgets.menu', session('sys_info')['top_menu'], 'menus')
                <!-- /.menu -->

            </div>
        </div>
        <!-- /.sidebar -->

        <div id="tooltip"></div>
    </div>