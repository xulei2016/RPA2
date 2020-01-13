<div class="alerts">
    @if(isset($data['alerts']))
        @foreach($data['alerts'] as $alert)

            <div class="alert alert-{{ $alert['type']  }} alert-dismissible" >
                <button type="button" class="close" data-id="{{ $alert['id'] }}">×</button>
                <h5><i class="fa fa-warning"></i> {{ $alert['title'] }}!</h5>
                {{ $alert['content'] }}
            </div>

        @endforeach
    @endif
</div>