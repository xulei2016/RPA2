@component('admin.widgets.viewForm')
    @slot("title")
        查看
    @endslot
    @slot("formContent")
        <div class="card">
            <div class="card-body">
            <pre style="white-space: normal;">
                {!! $sysMail->content !!}
            </pre>
            </div>
        </div>
    @endslot
    @slot("formScript")
    @endslot
@endcomponent
