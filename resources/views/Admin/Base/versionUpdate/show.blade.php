<div class="card card-default card-outline card-outline-primary">
    <div class="card-header">
        @if($info->type == 3)
            <span class="x-tag x-tag-sm x-tag-danger">紧急维护</span>
        @elseif($info->type == 2)
            <span class="x-tag x-tag-sm x-tag-primary">版本升级</span>
        @else
            <span class="x-tag x-tag-sm x-tag-success">正常更新</span>
        @endif
        ------
        {{$info->created_at}}

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="col-sm-12">
            <label for="">描述:</label>
        </div>
        <div class="col-sm-12">
            <pre style="white-space: normal;">
                {!! $info->desc !!}
            </pre>
        </div>
        <hr>
        <div class="col-sm-12">
            <label for="">具体内容:</label>
        </div>
        <div class="col-sm-12">
            <pre style="white-space: normal;">
                {!! $info->content !!}
            </pre>
        </div>

    </div>
</div>
