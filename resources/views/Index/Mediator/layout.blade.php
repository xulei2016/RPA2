<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0,user-scalable=0" />
    <link rel="stylesheet" href="{{asset('css/index/mediator/skin.css')}}">
    <title>居间人认证</title>
</head>
<body style="background-color:#F8F8F8">
<div id="app">
   @yield('component')
</div>
</body>

<script src="{{asset('js/index/mediator.js')}}?id={{ rand() }}"></script>
<script src="{{ URL::asset('/include/jquery/jquery.min.js')}} "></script>
</html>
