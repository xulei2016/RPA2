@component('Admin.widgets.editForm')
    @slot('formContent')
       
        <div class="form-group row">
            <label for="jys_id" class="col-sm-12 control-label"><span class="must-tag">*</span>交易所</label>
            <div class="col-sm-12">
                <select name="jys_id" id="jys_id" class="form-control">
                    <option value="">未选择</option>
                    @foreach($jys as $v)                       
                        @if($info->jys_id == $v->id)
                            <option selected value="{{$v->id}}">{{$v->name}}</option>
                        @else
                            <option value="{{$v->id}}">{{$v->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="pz_id" class="col-sm-12 control-label"><span class="must-tag">*</span>品种</label>
            <div class="col-sm-12">
                <select name="pz_id" id="pz_id" class="form-control">
                    @foreach($pz as $v)
                        @if($info->pz_id == $v->id)
                            <option selected value="{{$v->id}}">{{$v->name}}</option>
                        @else
                            <option value="{{$v->id}}">{{$v->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="pz_id" class="col-sm-12 control-label"><span class="must-tag">*</span>合约代码(填写后四位数,如au2001合约,只需填写2001即可)</label>
            <div class="col-sm-12">
                <input value="{{substr($info->hydm, -4)}}" type="text" name="hydm" id="hydm" class="form-control"  />
            </div>
        </div>

        <div class="form-group row">
            <label for="date" class="col-sm-12 control-label"><span class="must-tag">*</span>上市日期</label>
            <div class="col-sm-12">
                <input value="{{$info->date}}" type="text" name="date" id="date" class="form-control" />
            </div>
        </div>

        <input type="hidden" id="id" name="id" value="{{$info->id}}">


    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/contract/extra/edit.js')}}"></script>
    @endslot
@endcomponent
