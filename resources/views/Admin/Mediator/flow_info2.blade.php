@component('Admin.widgets.viewForm')
    @slot('title')
        查看
    @endslot
    @slot('formContent')
        <div class="card card-primary card-outline">
            <div class="card-body">
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="tab-pane active" id="info">
                            <table class="table table-bordered table-striped table-hover table-base">
                                <tr>
                                    <th>变更内容</th>
                                    <th>变更前</th>
                                    <th>变更后</th>
                                </tr>
                                @foreach($changelist as $v)
                                <tr>
                                    <td>{{ $v->name }}</td>
                                    <td>{{ $v->old }}</td>
                                    <td>{{ $v->new }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    @endslot

    @slot('formScript')
    @endslot
@endcomponent