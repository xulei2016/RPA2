

<div id="toolbar">
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
    <a class="btn btn-default btn-sm" data-toggle="collapse" data-target="#search-group" href="javascript:void(0)">
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>筛选器
    </a>

    {{ $operation }}

</div>