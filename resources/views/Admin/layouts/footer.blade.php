<!-- daterangepicker -->
<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<!-- ChartJS -->
<script src="{{URL::asset('/include/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{URL::asset('/include/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{URL::asset('/include/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('/include/jqvmap/maps/jquery.vmap.world.js')}}"></script>
{{-- pjax --}}
<script src="{{URL::asset('/include/jquery-pjax/JQuery.pjax.js')}}"></script>
{{-- toastr --}}
<script src="{{URL::asset('/include/toastr/toastr.min.js')}}"></script>
{{-- sweetalert2 --}}
<script src="{{URL::asset('/include/sweetalert2/sweetalert2.js')}}"></script>
{{-- icheck --}}
<script src="{{URL::asset('/include/iCheck/icheck.min.js')}}"></script>
{{-- nprogress --}}
<script src="{{URL::asset('/include/nprogress/nprogress.js')}}"></script>
<!-- jquery-form -->
<script src="{{URL::asset('/include/jquery-form/jquery.form.js')}}"></script>
<!-- jquery-validate -->
<script src="{{URL::asset('/include/jquery-validate/jquery.validate.min.js')}}"></script>
<script src="{{URL::asset('/include/jquery-validate/localization/messages_zh.min.js')}}"></script>
<!-- Bootstrap4 -->
<script src="{{URL::asset('/include/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- bootstrap-switch -->
<script src="{{URL::asset('/include/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
{{-- bootstrap-table --}}
<script src="{{URL::asset('/include/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{URL::asset('/include/bootstrap-table/local/bootstrap-table-zh-CN.js')}}"></script>
{{-- laydate --}}
<script src="{{URL::asset('/include/laydate/laydate.js')}}"></script>
{{-- plupload --}}
<script src="{{URL::asset('/include/plupload/js/plupload.full.min.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{URL::asset('/include/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{URL::asset('/include/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('/include/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{URL::asset('/include/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{URL::asset('/include/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- FastClick -->
<script src="{{URL::asset('/include/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('/include/adminlte/js/adminlte.js')}}"></script>
<!-- select2 -->
<script src="{{URL::asset('/include/select2/js/select2.js')}}"></script>
<!-- toastr -->
<script src="{{URL::asset('/include/toastr/toastr.js')}}"></script>
<script src="{{URL::asset('/js/app.js')}}"></script>
<script>
    //消息通知laravel-echo
    let socket = {
        userId : {{ Auth::user()->id }}
    }
</script>
<script src="{{URL::asset('/js/app.js')}}"></script>
<script src="{{URL::asset('/js/admin/main.js')}}"></script>
