<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('admin.name') }} @if($header) | {{ $header }}@endif</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Styles -->
    <link href="{{URL::asset('/css/admin/common/main.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/include/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/include/nprogress/nprogress.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/include/iCheck/skins/minimal/blue.css')}}" rel="stylesheet">

    <!-- script -->
    <script src="{{URL::asset('/include/jquery/jquery-3.3.1.min.js')}}"></script>
    <script src="{{URL::asset('/js/admin/syslimit.js')}}"></script>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition {{config('admin.skin')}} {{join(' ', config('admin.layout'))}}">
<div class="wrapper">

    @include('admin.layouts.header')

    @include('admin.layouts.sidebar')

    <div class="content-wrapper" id="pjax-container">
        @yield('content')
        {!! Admin::script() !!}
    </div>

    @include('admin::partials.footer')

</div>

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
</script>

<!-- REQUIRED JS SCRIPTS -->
@include('admin.inner.footer')

</body>
</html>
