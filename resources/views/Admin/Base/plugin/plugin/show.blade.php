<div class="card">
    <div class="card-header">
        <h3 class="card-title">查看版本</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="inner-section">
            <div id="content">
                <table class="table table-striped table-hover table-bordered table-base">
                    <tbody>
                    <tr>
                        <th>名称</th>
                        <th>版本号</th>
                    </tr>
                        @foreach($versions as $k => $v)
                            <tr>
                            <td>{{$v->show_name}}</td>
                            <td>{{$v->version}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>