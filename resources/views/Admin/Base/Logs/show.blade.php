@component('admin.widgets.viewForm')
    @slot("title")
        查看
    @endslot
    @slot("formContent")
        <div class="card">
            <!-- /.card-header -->
            <div class="card-body">
                <pre style="white-space: normal;">
                {{ $info->data }}
                </pre>
            </div>
        </div>
    @endslot
    @slot("formScript")
    @endslot
@endcomponent
