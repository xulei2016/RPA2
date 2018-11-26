<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">
            {{ $title or '修改操作' }}
        </h3>
    </div>

    <form class="form-horizontal" id="form" onsubmit="add($(this));return false;">
        <div class="box-body">
            {{ $formContent }}
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            {{ method_field('PATCH')}}
            <button type="submit" class="btn btn-info pull-right" id="save">提交</button>
            <div class="checkbox pull-right" style="margin-right:10px;"><label><input type="checkbox" class="minimal" id="form-continue">继续修改</label></div>
        </div>
        <!-- /.box-footer -->
    </form>
</div>
<script>
    //iCheck for checkbox and radio inputs
    $(document).ready(function(){
        $('#modal input.minimal').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
    });
</script>