<style>
    table th,td{
        text-align: center;
    }
</style>
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">查看操作</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="inner-section">
            <div id="content">
                <table class="table table-striped table-hover table-bordered table-base">
                    <tbody>
                    <tr>
                        <th>批次</th>
                        <th>批号</th>
                        <th>包数</th>
                        <th>等级</th>
                        <th>备注</th>
                    </tr>
                    @foreach($batch as $k => $v)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>{{$v->pihao}}</td>
                            <td>{{$v->package}}</td>
                            <td>{{$v->level}}</td>
                            @if(isset($v->remark))
                                <td><span class="text-danger">{{$v->remark}}</span></td>
                            @endif
                            @if(isset($v->state))
                                @if($v->state == 1)
                                    <td><span class="text-danger">被替包</span></td>
                                @else
                                    <td> </td>
                                @endif
                            @endif
                            @if(!isset($v->remark) && !isset($v->state))
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                    <tr>
                        <th>申请入库仓库</th>
                        <td colspan="4">{{ $info->sqrkck }}</td>
                    </tr>
                    <tr>
                        <th>仓库代码</th>
                        <td colspan="4">{{ $info->ckdm }}</td>
                    </tr>
                    <tr>
                        <th>客户编码</th>
                        <td colspan="4">{{ $info->khbm }}</td>
                    </tr><tr>
                        <th>客户名称</th>
                        <td colspan="4">{{ $info->khmc }}</td>
                    </tr>
                    <tr>
                        <th>客户联系人</th>
                        <td colspan="4">{{ $info->khlxr }}</td>
                    </tr>
                    <tr>
                        <th>客户联系电话</th>
                        <td colspan="4">{{ $info->khlxdh }}</td>
                    </tr>
                    <tr>
                        <th>生产年度</th>
                        <td colspan="4">{{ $info->scnd }}</td>
                    </tr>
                    <tr>
                        <th>商品产地省份</th>
                        <td colspan="4">{{ $info->spcdsf }}</td>
                    </tr>
                    <tr>
                        <th>商品产地地址</th>
                        <td colspan="4">{{ $info->spcdzz }}</td>
                    </tr>
                    <tr>
                        <th>预报数量</th>
                        <td colspan="4">{{ $info->ybsl }}</td>
                    </tr>
                    <tr>
                        <th>包装规格</th>
                        <td colspan="4">{{ $info->bzgg }}</td>
                    </tr>
                    <tr>
                        <th>加工时间</th>
                        <td colspan="4">{{ $info->jgsjksnf }}-{{$info->jgsjksyf}} 至 {{$info->jgsjjsnf}}-{{$info->jgsjjsyf}}</td>
                    </tr>
                    <tr>
                        <th>加工单位代码</th>
                        <td colspan="4">{{ $info->jgdwdm }}</td>
                    </tr>
                    <tr>
                        <th>加工单位</th>
                        <td colspan="4">{{ $info->jgdw }}</td>
                    </tr>
                    <tr>
                        <th>是否为保税仓单</th>
                        <td colspan="4">{{ $info->sfwbscd }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="/include/bootstrap/dist/js/bootstrap.min.js"></script>