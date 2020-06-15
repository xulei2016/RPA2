<table class="table table-bordered table-striped table-hover">
    <tbody>
        <tr>
            <td>姓名</td>
            <td>{{ $info['KHXM'] }}</td>
            <td>开户日期</td>
            <td>{{ $info['KHRQ'] }}</td>
        </tr>
        <tr>
            <td>资金账号</td>
            <td>{{$info['ZJZH'] }}</td>
            <td>客户号</td>
            <td>{{$info['KHH'] }}</td>
        </tr>
        <tr>
            <td>柜台手机</td>
            <td>{{$info['GTSJ'] }}</td>
            <td>手机</td>
            <td>{{$info['SJ'] }}</td>
        </tr>
        <tr>
            <td>证件编号</td>
            <td>{{$info['ZJBH'] }}</td>
            <td>证件地址</td>
            <td>{{$info['SFZDZ'] }}</td>
        </tr>
        <tr>
            <td>风险要素</td>
            <td>{{$info['FXYS'] }}</td>
            <td>同步客户</td>
            <td><a href="javascript:;" id="sync" class="btn btn-sm btn-primary">同步</a></td>
        </tr>
    </tbody>
</table>
<script>
    $(function(){
        //全部已读
        $("#sync").on('click', function(){
            Swal({
                title: "是否同步该客户",
                type: "info",
                showCancelButton: true,
                confirmButtonText: "确认",
                showLoaderOnConfirm: true,
                cancelButtonText: "取消",
            }).then(function(res) {
                if(res.value) {
                    $.post('/admin/rpa_crm_customer_sync', {zjzh: "{{$info['ZJZH'] }}"}, function(res){
                        Swal(res.info, '', 'success');
                    })
                    
                }
            });
        })
    })
</script>