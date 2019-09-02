<link rel="stylesheet" href="{{URL::asset('/include/bootstrap/dist/css/bootstrap.min.css')}}">
<style>
  .error-page{
    min-height: 200px;
    padding: 80px;
  }
</style>
<div class="error-page">
    <h2 class="headline text-yellow"> 403</h2>

    <div class="error-content">
      <h3><i class="fa fa-warning text-yellow"></i> 槽糕! 您没有访问权限哦.</h3>

      <p>
        您没有访问权限.
        你可以 <b>联系管理员授权处理</b>.
      </p>
    </div>
    <!-- /.error-content -->
</div>
<script src="{{URL::asset('/include/bootstrap/dist/js/bootstrap.min.js')}}"></script>