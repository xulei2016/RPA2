<!-- AdminLTE App -->
<script src="{{URL::asset('/js/admin/common/adminlte.min.js')}}"></script>
<!-- Scripts -->
<script src="{{URL::asset('/include/jquery-pjax/JQuery.pjax.js')}}"></script>
<script src="{{URL::asset('/include/nprogress/nprogress.js')}}"></script>

<!-- jQuery UI 1.11.4 -->
{{-- <script src="{{URL::asset('/include/jquery-ui/jquery-ui.min.js')}}"></script> --}}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
{{--<script>--}}
  {{--$.widget.bridge('uibutton', $.ui.button);--}}
{{--</script>--}}
<!-- Bootstrap 3.3.7 -->
<script src="{{URL::asset('/include/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- Bootstrap-table -->
<script src="{{URL::asset('/include/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{URL::asset('/include/bootstrap-table/local/bootstrap-table-zh-CN.js')}}"></script>
<!-- daterangepicker -->
 <script src="{{URL::asset('/include/moment/min/moment.min.js')}}"></script>
<script src="{{URL::asset('/include/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- switch -->
<script src="{{URL::asset('/include/bootstrap-switch/js/bootstrap-switch.min.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
{{-- <script src="{{URL::asset('include/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script> --}}
<!-- Slimscroll -->
<script src="{{URL::asset('/include/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- toastr -->
<script src="{{URL::asset('/include/toastr/toastr.min.js')}}"></script>
<!-- sweetalert2 -->
<script src="{{URL::asset('/include/sweetalert2/sweetalert2.min.js')}}"></script>
<!-- select2 -->
<script src="{{URL::asset('/include/select2/dist/js/select2.min.js')}}"></script>
<!-- jquery form -->
<script src="{{URL::asset('/include/jquery-form/jquery.form.js')}}"></script>
<!-- iCheck -->
<script src="{{URL::asset('/include/iCheck/iCheck.min.js')}}"></script>
<!-- plugupload -->
<script src="{{URL::asset('/include/plupload/js/plupload.full.min.js')}}"></script>
<!-- CKEDITOR -->
 <script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>
<!-- laydate -->
<script src="{{URL::asset('/include/laydate/laydate.js')}}"></script>
<!-- validate -->
<script src="{{URL::asset('/include/jquery-validate/jquery.validate.min.js')}}"></script>
<script src="{{URL::asset('/include/jquery-validate/localization/messages_zh.min.js')}}"></script>
<!-- fix ie -->
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('/js/admin/common/demo.js')}}"></script>
<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
<script src="/js/app.js"></script>
<script src="{{URL::asset('/js/admin/main.js')}}"></script>
<script>
 //消息通知laravel-echo
 let userId = {{ Auth::user()->id }}
 Echo.private('App.Models.Admin.Admin.SysAdmin.' + userId).notification(function(notification){
   let typeName = "";
   if(notification.typeName == 1){
     typeName = "系统公告";
   }else if(notification.typeName == 2){
     typeName = "RPA通知";
   }else{
     typeName = "管理员通知";
   }
   let html = "";
   html += '<div class="notify-wrap">'
           + '<div class="notify-title">' + typeName + '<span class="notify-off"><i class="icon iconfont">&#xe6e6;</i></span></div>'
           + '<div class="notify-title"><a href="JavaScript:void(0);" url="/admin/sys_message_list/view/'+ notification.id +'" onclick="operation($(this));" title="查看站内信息">' + notification.title + '</a><div>'
           + '<div class="notify-content">' + notification.content + '</div>'
           + '</div>';

   $("body").append(html);
   $(".notify-wrap").slideDown(2000);
   setTimeout(function(){
     $(".notify-wrap").slideUp(2000);
   },8000);
  });
</script>