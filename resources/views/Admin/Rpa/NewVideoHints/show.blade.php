@component('admin.widgets.viewForm')
    @slot("title")
        查看
    @endslot
    @slot("formContent")

        <div class="box box-info">
            <div class="box-body">
                <pre style="white-space: normal;">
                        {{ $info->jsondata }}
                </pre>
            </div>
        </div>
    @endslot
    @slot("formScript")
    @endslot
@endcomponent
