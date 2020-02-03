<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ config('admin.name') }} </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- jQuery -->
    <script src="{{URL::asset('/include/jquery/jquery.min.js')}}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{URL::asset('/include/font-awesome/css/font-awesome.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{URL::asset('/include/iCheck/all.css')}}">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap3/css/bootstrap.glyphicon.css')}}">
    <!-- bootstrap-switch -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css')}}">
    <!-- bootstrap-table -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-table/bootstrap-table.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{URL::asset('/include/adminlte/css/adminlte.min.css')}}">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="{{URL::asset('/include/sweetalert2/sweetalert2.min.css')}}">
    <!-- nprogress -->
    <link rel="stylesheet" href="{{URL::asset('/include/nprogress/nprogress.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{URL::asset('/include/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/select2/css/select2.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/toastr/toastr.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/admin/common/main.css')}}">
    {{--  simple cale  --}}
    <link rel="stylesheet" href="{{URL::asset('/include/simple-calendar/stylesheets/simple-calendar.css')}}">
    <script>
        function LA() {}
        LA.token = '{{ csrf_token() }}';
    </script>
</head>