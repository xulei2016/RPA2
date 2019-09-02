

<div @isset($toolbar) id="{{ $toolbar }}" @else id="toolbar" @endisset>

    @isset($selftool) {!! $selftool !!} @endisset

    @isset($listoperation_show)

        @if($dontoperation_show)

            <div class="btn-group" >
                <button type="button" class="btn btn-default btn-sm">操作</button>
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
        
                    {{ $listsOperation }}
                    
                </ul>
            </div>

        @endif

    @else

        <div class="btn-group" >
            <button type="button" class="btn btn-default btn-sm">操作</button>
            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu" role="menu">

                {{ $listsOperation }}
                
            </ul>
        </div>

    @endisset

    @isset($operation_show)

        @if($operation_show)

            <a class="btn btn-default btn-sm" data-toggle="collapse" data-target="#search-group" href="javascript:void(0)">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>筛选器
            </a>

        @endif

    @else

        <a class="btn btn-default btn-sm" data-toggle="collapse" data-target="#search-group" href="javascript:void(0)">
            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>筛选器
        </a>

    @endisset

    {{ $operation }}

</div>