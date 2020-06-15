@component('admin.widgets.editForm')
    @slot('formContent')

        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>回访状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="status" value="1" checked>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <label for="" class="col-sm-2 control-label"><span class="must-tag">*</span>回访方式</label>
                <div class="col-sm-10">
                    <input type="radio" value="2" name="reviewType" id="dh" checked> <label for="dh">电话</label>
                    <input type="radio" value="1" name="reviewType" id="dx"> <label for="dx">短信</label>
                </div>
                <br>
                <label for="khyj" class="col-sm-2 control-label">客户意见</label>
                <div class="col-sm-10">
                    <textarea id="khyj" name="khyj" cols="60" rows="3"></textarea>
                </div>
            </div>
            <div class="row hidden">
                <label for="khyj" class="col-sm-2 control-label"><span class="must-tag">*</span>失败原因</label>
                <div class="col-sm-10">
                    <input type="radio" value="无人接听" name="reason" id="wrjt"> <label for="wrjt">无人接听 &nbsp;&nbsp;</label>
                    <input type="radio" value="占线" name="reason" id="zx">  <label for="zx">占线 &nbsp;&nbsp;</label>
                    <input type="radio" value="停机" name="reason" id="tj">  <label for="tj">停机 &nbsp;&nbsp;</label>
                    <input type="radio" value="关机" name="reason" id="gj">  <label for="gj">关机 &nbsp;&nbsp;</label>
                    <input type="radio" value="短信呼" name="reason" id="dxh"> <label for="dxh">短信呼 &nbsp;&nbsp;</label>
                    <br>
                    <input type="radio" value="无法接通" name="reason" id="wfjt"> <label for="wfjt">无法接通</label>
                    <input type="radio" value="错号" name="reason" id="ch"> <label for="ch">错号 &nbsp;&nbsp;</label>
                    <input type="radio" value="拒接" name="reason" id="jj"> <label for="jj">拒接 &nbsp;&nbsp;</label>
                    <input type="radio" value="非本人接听" name="reason" id="fbenjt"> <label for="fbenjt">非本人接听 &nbsp;&nbsp;</label>
                    <input type="radio" value="其他" name="reason" id="qita"> <label for="qita">其他 &nbsp;&nbsp;</label>
                </div>
            </div>
            <div class="row hidden">
                <label for="khyj" class="col-sm-2 control-label">备注</label>
                <div class="col-sm-10">
                    <textarea id="bz" name="bz" cols="60" rows="3"></textarea>
                </div>
            </div>
        </div>
        {{ method_field('PATCH')}}
        <input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/Reviewtables/edit.js')}}"></script>
    @endslot
@endcomponent