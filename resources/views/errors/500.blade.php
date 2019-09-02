<link rel="stylesheet" href="{{URL::asset('/include/bootstrap/dist/css/bootstrap.min.css')}}">
<style>
  .error-page{
    min-height: 200px;
    padding: 80px;
  }
</style>
<div class="error-page">
    <h2 class="headline text-danger"> 500</h2>

    <div class="error-content">
      <h3><i class="fa fa-warning text-red"></i> 槽糕! 服务器错误啦.</h3>

      <p>
        您正在访问的页面不存在.
        你可以 <a href="/admin">返回首页</a>.
      </p>
    </div>
    <!-- /.error-content -->
</div>
<script src="{{URL::asset('/include/bootstrap/dist/js/bootstrap.min.js')}}"></script>