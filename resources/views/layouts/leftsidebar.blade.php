<!-- Left Sidebar  -->
<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>
                <li>
                    <a class="has-arrow" href="{{ route('home') }}" aria-expanded="false">
                        <i class="fa fa-dashboard"></i>
                        <span class="hide-menu">Главная</span>
                    </a>
                </li>
                <li class="nav-label">Модель</li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-building"></i>
                        <span class="hide-menu">Устройства</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li>
                            <a href="{{ route('devices.index') }}">
                                <i class="fa fa-building"></i>
                                Контроллеры
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('termostats.index') }}">
                                <i class="fa fa-tasks"></i>
                                Термостаты
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-tachometer"></i>
                                Датчики движения
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-tachometer"></i>
                                Счетчики
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="{{ route('objects.index') }}" aria-expanded="false">
                        <i class="fa fa-cube"></i>
                        <span class="hide-menu">Объекты</span></a>
                </li>
                <li>
                    <a class="has-arrow" href="{{ route('rooms.index') }}" aria-expanded="false">
                        <i class="fa fa-home"></i><span class="hide-menu">Помещения</span></a>
                </li>
                <li>
                    <a class="has-arrow" href="{{ route('views.index') }}" aria-expanded="false">
                        <i class="fa fa-object-group"></i><span class="hide-menu">Отображения</span></a>
                </li>
                <li>
                    <a class="has-arrow" href="{{ route('scenes.index') }}" aria-expanded="false">
                        <i class="fa fa-image"></i><span class="hide-menu">Сцены</span></a>
                </li>

                <li class="nav-label">Настройки</li>
                <li> <a class="has-arrow" href="{{ route('network.edit') }}" aria-expanded="false">
                        <i class="fa fa-plug"></i><span class="hide-menu">Сеть и VPN</span></a></li>
                <li> <a class="has-arrow" href="#" aria-expanded="false">
                        <i class="fa fa-bell-o"></i><span class="hide-menu">Оповещения</span></a></li>
                <li> <a class="has-arrow" href="{{ route('menu.index') }}" aria-expanded="false">
                        <i class="fa fa-th-list"></i><span class="hide-menu">Меню</span></a></li>
                <li> <a class="has-arrow" href="{{ route('scripts.index') }}" aria-expanded="false">
                        <i class="fa fa-flash"></i><span class="hide-menu">Скрипты</span></a></li>
                <li> <a class="has-arrow" href="{{ route('events.index') }}" aria-expanded="false">
                        <i class="fa fa-calendar"></i><span class="hide-menu">События</span></a></li>

                <li class="nav-label">Диагностика</li>
                <li> <a class="has-arrow" href="{{ route('logs.index') }}" aria-expanded="false">
                        <i class="fa fa-list"></i><span class="hide-menu">Логирование</span></a></li>
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

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</div>
<!-- End Left Sidebar  -->