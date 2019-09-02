@extends('admin.layouts.wrapper-content')

@section('content')

  <style>
    .error-page{
      min-height: 200px;
      padding: 80px;
    }
  </style>
  <div class="error-page">
      <h2 class="headline text-yellow"> 404</h2>

      <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> 槽糕! 页面找不到啦.</h3>

        <p>
          您正在访问的页面不存在.
          你可以 <a href="/admin">返回首页</a>.
        </p>
      </div>
      <!-- /.error-content -->
  </div>
  
@endsection
