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
                            <th>黑名单</th>
                            <td>
                                @if($apiip->black_list)
                                    @foreach($apiip->black_list as $k=>$v)
                                            {{$k}} == {{$v}} <br/>
                                    @endforeach
                                @else
                                    无
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>白名单</th>
                            <td>
                                @if($apiip->white_list)
                                    @foreach($apiip->white_list as $k=>$v)
                                        {{$k}} == {{$v}} <br/>
                                    @endforeach
                                @else
                                    无
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>