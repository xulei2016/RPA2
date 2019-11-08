@component('Admin.widgets.addForm')
    @slot('formContent')
        <div class="card card-primary">
            <div class="card-header">
                交易所和品种
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="jys_id" class="col-sm-12 control-label"><span class="must-tag">*</span>交易所</label>
                    <div class="col-sm-12">
                        <select name="jys_id" id="jys_id" class="form-control">
                            <option value="">未选择</option>
                            @foreach($jys as $v)
                                <option value="{{$v->id}}">{{$v->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="pz_id" class="col-sm-12 control-label"><span class="must-tag">*</span>品种</label>
                    <div class="col-sm-12">
                        <select name="pz_id" id="pz_id" class="form-control">
                            <option value="">未选择</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="card card-primary">
            <div class="card-header">品种费用</div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="pzfy_jysxf" class="col-sm-12 control-label"><span class="must-tag">*</span>交易手续费</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="pzfy_jysxf" id="pzfy_jysxf" placeholder="交易手续费">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pzfy_rnfy" class="col-sm-12 control-label">日内费用</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="pzfy_rnfy" id="pzfy_rnfy" placeholder="日内费用">
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-primary">
            <div class="card-header">特殊期货合约 </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="" class="col-sm-12 control-label">
                        <span class="must-tag">*</span>请选择月份
                    </label>
                    <div class="row col-sm-12">
                        <div class="col-sm-1">
                            <label for="">全选 </label> <input type="checkbox" id="allCheck" />
                        </div>
                        <div class="col-sm-1">
                            <label for="">反选 </label> <input type="checkbox" id="reverseCheck" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr>
                        <div class="row">
                        @foreach(range(1, 12) as $v)
                            <div class="col-sm-2">
                                <label for="">{{$v}}月</label>
                                <input type="checkbox" class="form-control hy-month" name="hy_month[]" id="hy_month" placeholder="" value="{{$v}}">
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group row">
            <label for="has_online" class="col-sm-12 control-label">是否有上市合约</label>
            <div class="switch col-sm-12">
                <input type="checkbox" name="has_online" id="has_online"   />
            </div>
        </div>

        <div id="online-contract" class="card card-primary" style="display:none">
            <div class="card-header">新合约上市时间费用</div>
            <div class="card-body">
                <div class="form-group">
                    交割月前
                    <input type="text" value="" name="xhy_month" id="xhy_month" class="form-control col-sm-1" style="display: inline-block">
                    个月第
                    <input type="text" value="" name="xhy_day" id="xhy_day" class="form-control col-sm-1" style="display: inline-block">
                    个
                    <select name="xhy_day_type" id="xhy_day_type" class="form-control col-sm-2" style="display: inline-block">
                        <option value="">日</option>
                        <option value="1">自然日</option>
                        <option value="2">交易日</option>
                    </select>
                    (后第
                    <input type="text" value="" name="xhy_day_after" id="xhy_day_after" class="form-control col-sm-1" style="display: inline-block">
                    交易日)
                    <span class="text-danger">注:括号中空白可不填</span>
                </div>

                <div class="form-group row">
                    <label for="xhy_jysxf" class="col-sm-12 control-label"><span class="must-tag">*</span>交易手续费</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="xhy_jysxf" id="xhy_jysxf" placeholder="交易手续费">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="xhy_rnfy" class="col-sm-12 control-label">日内费用</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="xhy_rnfy" id="xhy_rnfy" placeholder="日内费用">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="has_change" class="col-sm-12 control-label">是否有运行中调整</label>
            <div class="switch col-sm-12">
                <input type="checkbox" name="has_change" id="has_change"   />
            </div>
        </div>

        <div id="run-change" class="card card-primary" style="display: none">
            <div class="card-header">运行中调整时间标准</div>
            <div class="card-body">
                交割月前第
                <input type="text" value="" name="tz_month" id="tz_month" class="form-control col-sm-1" style="display: inline-block">
                个月的第
                <input type="text" value="" name="tz_day" id="tz_day" class="form-control col-sm-1" style="display: inline-block">
                个交易日起
                <hr >
                <div class="form-group row">
                    <label for="tz_jysxf" class="col-sm-12 control-label">交易手续费</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="tz_jysxf" id="tz_jysxf" placeholder="交易手续费">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tz_rnfy" class="col-sm-12 control-label">日内费用</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" name="tz_rnfy" id="tz_rnfy" placeholder="日内费用">
                    </div>
                </div>
            </div>
            <div class="card-footer"><span class="text-danger">*此处均不必填</span></div>
        </div>


    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/contract/detail/add.js')}}"></script>
    @endslot
@endcomponent
