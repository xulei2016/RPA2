<div class="card">
    <div class="card-header">
        <h3 class="card-title">下载</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="inner-section">
            <div id="content">
                <table class="table table-striped table-hover table-bordered table-base">
                    <tbody>
                    <tr>
                        <th class="text-center">名称</th>
                        <th class="text-center">说明</th>
                        <th class="text-center">版本号</th>
                        <th class="text-center">操作</th>
                    </tr>
                    @foreach($versions as $k => $v)
                        <tr>
                            <td class="text-center">{{$v->show_name}}</td>
                            <td class="text-center">{{$v->desc}}</td>
                            <td class="text-center">{{$v->version}}</td>
                            <td class="text-center"><a class="btn btn-sm btn-primary" href="/admin/rpa_download_plugin/download/{{$v->id}}">下载</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

</script>