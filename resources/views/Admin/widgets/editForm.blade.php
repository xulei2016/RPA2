<div class="modal-header with-border move">
    <h3 class="modal-title">
        {{ $title or '修改操作' }}
    </h3>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>

<form class="form-horizontal" id="form">
    <div class="modal-body">
        {{ $formContent }}
    </div>
    <!-- /.modal-body -->
    <div class="modal-footer">
        {{ method_field('PATCH')}}
        <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue" {{ $edit_continue or '' }}>继续{{ $title or '修改' }}</label></div>
        <button type="button" class="btn btn-success pull-right" id="save">{{ $title or '修改' }}</button>
    </div>
    <!-- /.modal-footer -->
</form>
{{ $formScript }}
<script>
    //iCheck for checkbox and radio inputs
    $(document).ready(function(){
        $('#modal input.minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
    });
</script>