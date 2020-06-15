@component('admin.widgets.viewForm')
@slot("title")
    下载
@endslot
@slot("formContent")
<style>
    #swal2-content{
        text-align: unset !important;
    }
</style>
<div class="card">
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
                            <td class="text-center">
                                <a class="btn btn-sm btn-primary" style="margin-right: 2px;" href="/admin/rpa_download_plugin/download/{{$v->id}}">下载</a>
                                @if($v->doc_id)
                                    <a class="btn btn-sm btn-primary versionDocument" item-id="{{ $v->doc_id }}">查看文档</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endslot
@slot("formScript")
    <script src="{{URL::asset('/js/admin/func/DownloadPlugin/show.js')}}"></script>
@endslot
@endcomponent