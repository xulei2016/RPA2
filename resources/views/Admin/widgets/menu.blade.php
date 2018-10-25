
    <div class="sidebar-nav @if($loop->first) sidebar-active @endif">
        <div class="sidebar-title" data-tip='tooltip'>
            <span class="icon">
                <i class="iconfont">
                {{ $menus['icon'] or '&#xe6b9; ' }}
                </i>
            </span>
            <span class="nav-title">{{ $menus['name'] }}</span>
            <span class="right active"><i class="iconfont">&#xe6c2;</i></span>
        </div>
        <ul class="sidebar-trans">
            @foreach ($menus['menus'] as $menu)
                @if($loop->first)
                    <li class="nav-item active" name="{{ $menu['unique_name'] }}" data-tip='tooltip'>
                        <a url='{{url("/admin/".$menu['unique_name'])}}' mid="{{$menu['id']}}" class="sidebar-trans">
                            <span class="icon"><i class="iconfont">
                                {{ $menu['icon'] or '&#xe606;' }}
                                </i>
                            </span>
                            <span class="nav-title">{{ $menu['name'] }}</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item" name="{{ $menu['unique_name'] }}" data-tip='tooltip'>
                        <a url='{{url("/admin/".$menu['unique_name'])}}' mid="{{$menu['id']}}" class="sidebar-trans">
                            <span class="icon"><i class="iconfont">
                                {{ $menu['icon'] or '&#xe606;' }}
                                </i>
                            </span>
                            <span class="nav-title">{{ $menu['name'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>