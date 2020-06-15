<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    @foreach($list as $k => $v)
        <li role="presentation" @if($k == 0) class="active" @endif>
            <a href="#{{$v['index']}}" aria-controls="{{$v['index']}}" role="tab" data-toggle="tab">{{$v['name']}}({{$v['phone']}})</a>
        </li>
    @endforeach  
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    @foreach($list as $k => $v)
        <div role="tabpanel" class="tab-pane @if($k == 0) active @endif" id="{{$v['index']}}">
            <div style="line-height:50px;padding-left:2px;">姓名:{{$v['name']}}, 手机号: {{$v['phone']}}</div>
            <table class="table table-hover">
                <tr class="title">
                    <th>操作</th>
                    <th>说明</th>
                    <th>时间</th>
                </tr>
                @foreach($v['list'] as $item) 
                    <tr>
                        {!! $item['time'] !!}
                        {!! $item['operation'] !!}
                        {!! $item['desc'] !!}
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach 
  </div>

</div>