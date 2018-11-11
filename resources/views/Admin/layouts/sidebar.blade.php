<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

          <!-- search form -->
          <form action="#" method="get" class="sidebar-form">
              <div class="input-group">
                  <input type="text" name="q" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                      <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                      </button>
                  </span>
              </div>
          </form>

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu" data-widget="tree">

              {{-- @inject('menus','App\Models\Admin\Base\SysMenu') --}}
              @inject('menus','App\Http\Controllers\Admin\Base\MenuController')

              {!! $menus->getMenuList() !!}

              <li><a href="https://adminlte.io/docs"><i class="fa fa-book"></i> <span>Documentation</span></a></li>

              <li class="header" data-rel="external">其他</li>
              <li data-rel="external"><a href="https://doc.fastadmin.net" target="_blank"><i class="fa fa-list text-red"></i> <span>BUG提交</span></a></li>
              <li data-rel="external"><a href="https://forum.fastadmin.net" target="_blank"><i class="fa fa-comment text-yellow"></i> <span>改进意见</span></a></li>
              <li data-rel="external"><a href="https://jq.qq.com/?_wv=1027&amp;k=487PNBb" target="_blank"><i class="fa fa-qq text-aqua"></i> <span>QQ交流群</span></a></li>

          </ul>
      </section>
      <!-- /.sidebar -->
  </aside>