@extends('admin.layouts.wrapper-content')

@section('content')

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

@endsection