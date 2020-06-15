@component('Admin.widgets.editForm')
    @slot('title')
        编辑
    @endslot
    @slot('formContent')
<link rel="stylesheet" href="{{ URL::asset('/include/fancybox/fancybox.css')}}">
<style>
    .td-input{
        width: 100%;
        border: none;
    }
</style>
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
                            <td>{{ $info->info->name }}</td>
                            <th>手机号码</th>
                            <td>{{ $info->info->phone }}</td>
                        </tr>
                        <tr>
                            <th>性别</th>
                            <td>
                                <select name="sex" class="td-input">
                                    <option value="">无</option>
                                    <option @if($info->sex == '男') selected @endif value="男">男</option>
                                    <option @if($info->sex == '女') selected @endif value="女">女</option>
                                    <option @if($info->sex == '非自然人') selected @endif value="非自然人">非自然人</option>
                                </select>
                            </td>
                            <th>所属部门</th>
                            <td>
                                <select name="dept_id" class="td-input">
                                    <option value="">无</option>
                                    @foreach($dept as $v)
                                        <option @if($info->dept_id == $v->id) selected @endif value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>客户经理工号</th>
                            <td><input name="manager_number" class="td-input" type="text" value="{{ $info->manager_number }}"></td>
                            <th>居间人编号</th>
                            <td><input name="number" class="td-input" type="text" value="{{ $info->number }}"></td>
                        </tr>
                        <tr>
                            <th>邮箱</th>
                            <td><input name="email" class="td-input" type="text" value="{{ $info->email }}"></td>
                            <th>教育背景</th>
                            <td>
                                <select name="edu_background">
                                    <option value="">无</option>
                                    @foreach($edu_background as $v)
                                        <option @if($info->edu_background == $v->value) selected @endif value="{{ $v->value }}">{{ $v->value }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>联系地址</th>
                            <td><input name="address" class="td-input" type="text" value="{{ $info->address }}"></td>
                            <th>邮编</th>
                            <td><input name="postal_code" class="td-input" type="text" value="{{ $info->postal_code }}"></td>
                        </tr>
                        <tr>
                            <th>职业</th>
                            <td>
                            <input name="profession" class="td-input" type="text" value="{{$info->profession}}">
                            </td>
                            <th>是否通过从业资格考试</th>
                            <td>
                                @if($info->is_exam)
                                    <span class="x-tag x-tag-sm x-tag-success">通过</span>
                                @else
                                    <span class="x-tag x-tag-sm x-tag-danger">未通过</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>从业资格合格证编号</th>
                            <td><input name="exam_number" class="td-input" type="text" value="{{ $info->exam_number }}"></td>
                            <th>从业资格合格证照片</th>
                            <td>
                                @if($info->is_exam)
                                    <div data-fancybox href="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->exam_img) }}">
                                        <img width="40" height="40" src="/admin/showImage?url={{ \Illuminate\Support\Facades\Crypt::encrypt($info->exam_img) }}" alt="从业资格合格证照片"  title="从业资格合格证照片">
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>开户日期</th>
                            <td>{{ $info->info->open_time }}</td>
                        </tr>
                    </table>
                </div>
                <div class="tab-pane" id="idCard">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th>出生日期</th>
                            <td><input name="birthday" class="td-input" type="text" value="{{ $info->birthday }}"></td>
                            <th>证件到期日</th>
                            <td><input name="sfz_date_end" class="td-input" type="text" value="{{ $info->sfz_date_end }}"></td>
                        </tr>
                        <tr>
                            <th>证件编号</th>
                            <td><input name="zjbh" class="td-input" type="text" value="{{ $info->zjbh }}"></td>
                            <th>身份证地址</th>
                            <td><input name="sfz_address" class="td-input" type="text" value="{{ $info->sfz_address }}"></td>
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
                        </tr>
                    </table>
                </div>
                <div class="tab-pane" id="bank">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th>开户银行</th>
                            <td>
                                <input name="bank_name" class="td-input" type="text" value="{{ $info->bank_name }}">
                            </td>
                            <th>银行网点</th>
                            <td><input name="bank_branch" class="td-input" type="text" value="{{ $info->bank_branch }}"></td>
                        </tr>
                        <tr>
                            <th>开户人</th>
                            <td><input name="bank_username" class="td-input" type="text" value="{{ $info->bank_username }}"></td>
                            <th>银行卡号</th>
                            <td><input name="bank_number" class="td-input" type="text" value="{{ $info->bank_number }}"></td>
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
                            <th>签字照片  <button type="button" class="btn btn-sm btn-success" data-title="{{ $info->id }}" onclick="rotate($(this));"><i class="fa fa-rotate-right"></i></button></th>
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
<input type="hidden" id="id" value="{{ $info->id }}">
<script src=" {{ URL::asset('/include/fancybox/fancybox.js')}} "></script>
<script>
    function rotate(obj){
        var url = "/admin/mediator/rotateImg";
        var id = obj.attr('data-title');
        $.post(url,{id:id},function(re){
            if(re.status == 200){
                var src = $("#sign_img").attr('src')+"&"+Math.random();
                $("#sign_img").attr('src',src);
                $("#sign_img").parent().attr('href',src);
            }
        })
    }
</script>
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/mediator/edit.js')}}"></script>
    @endslot
@endcomponent