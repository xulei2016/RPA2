@component('admin.widgets.editForm')
    @slot('formContent')

        <div class="panel box box-primary">
            <div class="panel-body">
                    <table class="table table-bordered table-striped table-hover table-base">
                        <tr>
                            <th width="20%" class="text-right">名称</th>
                            <th width="35%" class="text-left">内容</th>
                        </tr>
                        <tr>
                            <td class="text-right">姓名</td>
                            <td class="text-left">{{$info->name}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">身份证号码</td>
                            <td class="text-left">{{$info->sfz}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">联系电话</td>
                            <td class="text-left">{{$info->phone}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">地址</td>
                            <td class="text-left">{{$info->address}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">邮编</td>
                            <td class="text-left">{{$info->postcode}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">邮箱</td>
                            <td class="text-left">{{$info->email}}</td>
                        </tr>
                        <tr>
                            <td class="text-right">资金账号</td>
                            <td class="text-left">
                                <input type="text" class="form-control" id="zjzh" name="zjzh" value="{{$info->zjzh}}">
                            </td>
                        </tr>
                    </table>
            </div>
        </div>
        {{ method_field('PATCH')}}
        <input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/simulation/edit.js')}}"></script>
    @endslot
@endcomponent