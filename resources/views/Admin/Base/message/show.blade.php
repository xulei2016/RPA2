@component('admin.widgets.viewForm')
    @slot('formContent')

            {!! $notification->data['content'] !!}

    @endslot
    @slot('formScript')
    @endslot
@endcomponent