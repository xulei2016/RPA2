

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @if(session('keepMenu'))
                            {{ session('keepMenu')['title'] }}
                            <small>{{ session('keepMenu')['unique_name'] }}</small>
                        @else
                            首页
                            <small>仪表盘</small>
                        @endif
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/admin"><i class="fa fa-dashboard"></i> 首页</a></li>
                        <li class="breadcrumb-item active">
                            @if(session('keepMenu'))
                                {{ session('keepMenu')['title'] }}
                            @else
                                仪表盘
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

