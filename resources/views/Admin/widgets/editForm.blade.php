<div class="modal-header with-border">
    <h3 class="modal-title">
        {{ $title or '修改操作' }}
    </h3>
</div>

<form class="form-horizontal" id="form">
    <div class="modal-body">
        {{ $formContent }}
    </div>
    <!-- /.modal-body -->
    <div class="modal-footer">
        {{ method_field('PATCH')}}
        <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续修改</label></div>
        <button type="button" class="btn btn-success pull-right" id="save">提交</button>
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