<div class="modal-header move">
    <h3 class="modal-title">
        {{ $title or '添加操作' }}
    </h3>
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>

<form class="form-horizontal" id="form">
    <div class="modal-body">
            {{ $formContent }}
    </div>
    <!-- /.modal-body -->
    <div class="modal-footer">
        <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" id="form-continue" class="minimal icheck">继续添加</label></div>
        <button type="button" class="btn btn-warning" id="form-reset" onclick="RPA.form.reset($(this).parents('form'))">重置</button>
        <button type="button" class="btn btn-success pull-right" id="save">提交</button>
    </div>
    <!-- /.modal-footer -->
</form>

{{ $formScript }}

<script>
    //iCheck for checkbox and radio inputs
    $(document).ready(function(){
        // $('#modal input.minimal').iCheck({
        //     checkboxClass: 'icheckbox_minimal-blue',
        //     radioClass: 'iradio_minimal-blue',
        // });
    });
</script>