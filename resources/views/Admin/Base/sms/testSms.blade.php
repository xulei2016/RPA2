@component('admin.widgets.addForm', ['title' => '短信测试'])
    @slot('formContent')
        <style lang="scss">
            .json-item {
                width: 100%;
                height: 100%;
                &.string-box {
                    height: auto;
                    line-height: 20px;
                    overflow: hidden;
                    word-break: break-all;
                }
                .number {
                    color: #2FA0ED;
                }
                .string {
                    color: #F16222;
                }
                .boolean {
                    color: #00C099;
                }
                .null {
                    color: #CC33CC;
                }
                .key {
                    color: #424456;
                }
            }
        </style>
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill"
                   href="#single_send-home" role="tab" aria-controls="custom-content-above-home" aria-selected="true">单条短信测试</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill"
                   href="#batch_send" role="tab" aria-controls="custom-content-above-profile" aria-selected="false">批量发送测试</a>
            </li>
        </ul>
        <div class="tab-content" id="custom-content-above-tabContent">
            <div class="tab-pane fade show active" id="single_send-home" role="tabpanel"
                 aria-labelledby="single_send">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="gateway" class="col-sm-2 control-label"><span class="must-tag">*</span>选择通道</label>
                            <div class="col-sm-10">
                                @if($gateways)
                                    <select name="gateway" class="form-control">
                                        @foreach($gateways as $gateway)
                                            <option value="{{$gateway->unique_name}}">{{$gateway->name}} -- {{$gateway->available_list}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    暂无可用通道，请先设置可用通道！
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-sm-2 control-label"><span class="must-tag">*</span>手机号</label>
                            <div class="col-sm-10">
                                    <input required type="phone" class="form-control" name="phone" id="phone" placeholder="手机号">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="content" class="col-sm-2 control-label"><span class="must-tag">*</span>短信内容</label>
                            <div class="col-sm-10">
                                <textarea required class="form-control" name="content"></textarea>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="return" class="col-sm-2 control-label">返回内容</label>
                            <div class="col-sm-10">
                                <pre class="form-control json-item" id="return"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="batch_send" role="tabpanel"
                 aria-labelledby="batch_send">
                敬请期待，或者参与完善，^_^
            </div>
        </div>
    @endslot
    @slot('formScript')
        <script src="{{URL::asset('/js/admin/base/sms/testSms.js')}}"></script>
    @endslot
@endcomponent