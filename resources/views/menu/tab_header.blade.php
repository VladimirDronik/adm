<ul class="nav nav-tabs customtab" role="tablist">
    <li class="nav-item"> <a class="nav-link @if($active === 'groups') active @endif"
                             href="{{ route('menu.index') }}">
            <span class="hidden-sm-up"><i class="ti-home"></i></span>
            <span class="hidden-xs-down">Группы меню</span></a>
    </li>
    @foreach($groups as $group)
        <li class="nav-item"> <a class="nav-link @if($active === $group->id) active @endif"
                                 href="{{ route('menu.group.index', [$group->id]) }}">
                <span class="hidden-sm-up"><i class="ti-user"></i></span>
                <span class="hidden-xs-down">{{ $group->title }}</span></a>
        </li>
    @endforeach
</ul>