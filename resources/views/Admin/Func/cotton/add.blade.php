<div style="max-height: 300px; overflow: scroll;">
    <ul class="list-group file-group">
    </ul>
</div>

<div>
    <a href="javascript:void(0)" class="btn btn-block btn-default" id="txt_file"><i class="glyphicon glyphicon-upload"></i>选择文件上传</a>
    <input type="file" name="txt_file" id="uploadFile" multiple="" class="file-loading hidden">
</div>
<div class="report">
    <span class="title"></span>
    <ul class="list-group error-group">
    </ul>
</div>
<div class="modal-footer">
    <a class="btn btn-primary submit">开始上传</a>
    <a class="btn btn-danger"><span aria-hidden="true"> 取消上传</span></a>
</div>
<!-- Scripts -->
<script type="text/javascript" src="{{URL::asset('/js/admin/func/Cotton/add.js')}}"></script>