<div class="left-sidebar">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li>
                    <a class="has-arrow" href="{{ route('home') }}" aria-expanded="false">
                        <i class="fa fa-dashboard"></i>
                        <span class="hide-menu">Главная</span>
                    </a>
                </li>
                @can('devices')
                    <li class="nav-label">Модель</li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-building"></i>
                            <span class="hide-menu">Устройства</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li>
                                <a href="{{ route('termostats.index') }}">
                                    <i class="fa fa-tasks"></i>
                                    Датчики
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('devices.index') }}">
                                    <i class="fa fa-building"></i>
                                    Контроллеры
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('switches.index') }}">
                                    <i class="fa fa-bullseye"></i>
                                    Выключатели (кнопки)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dimmers.index') }}">
                                    <i class="fa fa-bullseye"></i>
                                    Диммеры
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('relays.index') }}">
                                    <i class="fa fa-bullseye"></i>
                                    Реле (розетки)
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('counts.index') }}">
                                    <i class="fa fa-bullseye"></i>
                                    Счетчики
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('objects')
                    <li>
                        <a class="has-arrow" href="{{ route('objects.index') }}" aria-expanded="false">
                            <i class="fa fa-cube"></i>
                            <span class="hide-menu">Объекты</span></a>
                    </li>
                @endcan
                @can('rooms')
                    <li>
                        <a class="has-arrow" href="{{ route('rooms.index') }}" aria-expanded="false">
                            <i class="fa fa-home"></i><span class="hide-menu">Помещения</span></a>
                    </li>
                @endcan
                @can('views')
                    <li>
                        <a class="has-arrow" href="{{ route('views.index') }}" aria-expanded="false">
                            <i class="fa fa-object-group"></i><span class="hide-menu">Отображения</span></a>
                    </li>
                @endcan
                @can('scenes')
                    <li>
                        <a class="has-arrow" href="{{ route('scenes.index') }}" aria-expanded="false">
                            <i class="fa fa-image"></i><span class="hide-menu">Сцены</span></a>
                    </li>
                @endcan
                <li class="nav-label">Настройки</li>
                @can('network')
                    <li> <a class="has-arrow" href="{{ route('network.edit') }}" aria-expanded="false">
                            <i class="fa fa-plug"></i><span class="hide-menu">Сеть и VPN</span></a></li>
                @endcan
                @can('scenes')
                    <li> <a class="has-arrow" href="{{ route('users.index') }}" aria-expanded="false">
                            <i class="fa fa-user-o"></i><span class="hide-menu">Пользователи</span></a></li>
                @endcan
                @can('scenes')
                    <li> <a class="has-arrow" href="{{ route('notifications.index') }}" aria-expanded="false">
                            <i class="fa fa-bell-o"></i><span class="hide-menu">Оповещения</span></a></li>
                @endcan
                <!--@can('menu')
                    <li> <a class="has-arrow" href="{{ route('menu.index') }}" aria-expanded="false">
                            <i class="fa fa-th-list"></i><span class="hide-menu">Меню</span></a></li>
                @endcan
                -->
                @can('scripts')
                    <li> <a class="has-arrow" href="{{ route('scripts.index') }}" aria-expanded="false">
                            <i class="fa fa-flash"></i><span class="hide-menu">Скрипты</span></a></li>
                @endcan
                @can('events')
                    <li> <a class="has-arrow" href="{{ route('events.index') }}" aria-expanded="false">
                            <i class="fa fa-calendar"></i><span class="hide-menu">События</span></a></li>
                @endcan
                @can('settings')
                    <li> <a class="has-arrow" href="{{ route('settings.index') }}" aria-expanded="false">
                            <i class="fa fa-cog "></i><span class="hide-menu">Настройки</span></a></li>
                @endcan
                <li class="nav-label">Диагностика</li>
                @can('logs')
                    <li> <a class="has-arrow" href="{{ route('logs.index') }}" aria-expanded="false">
                            <i class="fa fa-list"></i><span class="hide-menu">Логирование</span></a></li>
                @endcan
                @can('graphs')
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-bar-chart"></i><span class="hide-menu">Графики</span>
                        </a>
                        <ul aria-expanded="false" class="collapse">
                            <li>
                                <a href="{{ route('graphs.termostats.index') }}">
                                    <i class="fa fa-bar-chart"></i>
                                    Температура
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('graphs.humidities.index') }}">
                                    <i class="fa fa-bar-chart"></i>
                                    Влажность
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('graphs.lights.index') }}">
                                    <i class="fa fa-bar-chart"></i>
                                    Освещенность
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('graphs.counts.index') }}">
                                    <i class="fa fa-bar-chart"></i>
                                    Счетчики
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</div>