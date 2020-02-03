@component('Admin.widgets.viewForm')
    @slot('title')
        查看
    @endslot
    @slot('formContent')
        <div class="card card-primary card-outline">
    <div class="card-body">
        <div class="nav-tabs-custom">
            <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab" aria-expanded="false">基本信息</a></li>
                <li class="nav-item"><a class="nav-link" href="#idCard" data-toggle="tab" aria-expanded="false">身份证信息</a></li>
                <li class="nav-item"><a class="nav-link" href="#bank" data-toggle="tab" aria-expanded="false">银行卡信息</a></li>
                <li class="nav-item"><a class="nav-link" href="#other" data-toggle="tab" aria-expanded="false">其他信息</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="info">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th>姓名</th>
                            <td>{{ $info->name }}</td>
                            <th>性别</th>
                            <td>{{ $info->sex }}</td>
                        </tr>
                        <tr>
                            <th>所属部门</th>
                            <td>{{ $info->dept->name }}</td>
                            <th>手机号码</th>
                            <td>{{ $info->phone }}</td>
                        </tr>
                        <tr>
                            <th>出生日期</th>
                            <td>{{ $info->birthday }}</td>
                            <th>开户日期</th>
                            <td>{{ $info->open_time }}</td>
                        </tr>
                        <tr>
                            <th>客户经理工号</th>
                            <td>{{ $info->manager_number }}</td>
                            <th>居间人编号</th>
                            <td>{{ $info->number }}</td>
                        </tr>
                        <tr>
                            <th>邮箱</th>
                            <td>{{ $info->email }}</td>
                            <th>教育背景</th>
                            <td>{{ $info->edu_background }}</td>
                        </tr>
                        <tr>
                            <th>地址</th>
                            <td>{{ $info->address }}</td>
                            <th>邮编</th>
                            <td>{{ $info->postal_code }}</td>
                        </tr>
                        <tr>
                            <th>职业</th>
                            <td>{{ $info->profession }}</td>
                            <th>从业资格证号</th>
                            <td>{{ $info->exam_number }}</td>
                        </tr>
                        <tr>
                            <th>是否通过从业资格考试</th>
                            <td>
                                @if($info->is_exam)
                                    <span class="x-tag x-tag-sm x-tag-success">通过</span>
                                @else
                                    <span class="x-tag x-tag-sm x-tag-danger">未通过</span>
                                @endif
                            </td>
                            <th>从业资格证</th>
                            <td>
                                @if($info->is_exam)
                                    <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->exam_img) }}">
                                        <img width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->exam_img) }}" alt="从业资格证"  title="从业资格证">
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>居间人状态</th>
                            <td>
                                @if($info->status == 0)
                                    <span class="x-tag x-tag-sm x-tag-info">未完成</span>
                                @elseif($info->status == 1)
                                    <span class="x-tag x-tag-sm">正常</span>
                                @elseif($info->status == 2)
                                    <span class="x-tag x-tag-sm x-tag-danger">过期</span>
                                @elseif($info->status == 3)
                                    <span class="x-tag x-tag-sm x-tag-danger">注销</span>
                                @else
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane" id="idCard">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th>证件编号</th>
                            <td>{{ $info->zjbh }}</td>
                            <th>证件到期日</th>
                            <td>{{ $info->sfz_date_end }}</td>
                        </tr>
                        <tr>
                            <th>身份证正面照</th>
                            <td>
                                <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sfz_zm_img) }}">
                                    <img  width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sfz_zm_img) }}" alt="身份证正面照" title="身份证正面照">
                                </div>
                            </td>
                            <th>身份证反面照</th>
                            <td>
                                <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sfz_fm_img) }}">
                                    <img width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sfz_fm_img) }}" alt="身份证反面照" title="身份证反面照">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>手持身份证照</th>
                            <td>
                                <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sfz_sc_img) }}">
                                    <img width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sfz_sc_img) }}" alt="手持身份证照"  title="手持身份证照">
                                </div>
                            </td>
                            <th>身份证地址</th>
                            <td>{{ $info->sfz_address }}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane" id="bank">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th>开户银行</th>
                            <td>{{ $info->bank_name }}</td>
                            <th>银行网点</th>
                            <td>{{ $info->bank_branch }}</td>
                        </tr>
                        <tr>
                            <th>开户人</th>
                            <td>{{ $info->bank_username }}</td>
                            <th>银行卡号</th>
                            <td>{{ $info->bank_number }}</td>
                        </tr>
                        <tr>
                            <th>银行卡照片</th>
                            <td colspan="3">
                                <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->bank_img) }}">
                                    <img width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->bank_img) }}" alt="银行卡照片"  title="银行卡照片">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane" id="other">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th>比例(%)</th>
                            <td>{{ $info->rate }}</td>
                            <th>金融比例(%)</th>
                            <td>{{ $info->jr_rate }}</td>
                        </tr>
                        <tr>
                            <th>签字照片</th>
                            <td colspan="3">
                                <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sign_img) }}">
                                    <img id="sign_img" width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->sign_img) }}" alt="签字照片"  title="签字照片">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    @endslot

    @slot('formScript')
    @endslot
@endcomponent
