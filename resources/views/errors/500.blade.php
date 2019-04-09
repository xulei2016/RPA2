
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

      <form class="search-form">
        <div class="input-group">
          <input type="text" name="search" class="form-control" placeholder="Search">

          <div class="input-group-btn">
            <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i>
            </button>
          </div>
        </div>
        <!-- /.input-group -->
      </form>
    </div>
    <!-- /.error-content -->
</div>
