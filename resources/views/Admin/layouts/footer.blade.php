<!-- daterangepicker -->
<script src="{{URL::asset('/include/moment/min/moment.min.js')}}"></script>
<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<script src="/js/all.js"></script>
<script src="/js/app.js"></script>
<script>
  //消息通知laravel-echo
  let socket = {
    userId : {{ Auth::user()->id }}
  }
</script>
<script src="{{URL::asset('/js/admin/main.js')}}"></script>