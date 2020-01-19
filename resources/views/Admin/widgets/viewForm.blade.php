<div class="modal-header move">
    <h3 class="modal-title">
        {{ $title or '查看' }}
    </h3>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>

<form class="form-horizontal" id="form">
    <div class="modal-body">
            {{ $formContent }}
    </div>
</form>

{{ $formScript }}
