@component('admin.widgets.addForm')
@slot('formContent')

<div class="form-group row">
    <label for="khh" class="col-sm-2 control-label"><span class="must-tag">*</span>客户号</label>
    <div class="col-sm-10">
        <input type="text" class="form-control" name="khh" id="khh" placeholder="客户号">
        <span class="text-danger wrong"></span>
        <span class="text-success success"></span>
    </div>
</div>

<div class="form-group row">
    <label for="tid" class="col-sm-2 control-label"><span class="must-tag">*</span>品种选择</label>
    <div class="col-sm-10">
        <select name="tid" id="tid" class="form-control">
            @foreach($varietyList as $name)
                <option value="{{ $name['id'] }}">{{ $name['name'] }}</option>
            @endforeach
        </select>
    </div>
</div>
@endslot

@slot('formScript')
<script src="{{URL::asset('/js/admin/func/Oabremind/add.js')}}"></script>
@endslot
@endcomponent