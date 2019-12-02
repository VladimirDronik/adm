
<!-- header header  -->
<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- Logo -->
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('home') }}">
                <!-- Logo icon -->
                <b><img src="{{ asset('ela/images/logo.png') }}" alt="homepage" class="dark-logo" /></b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span><img src="{{ asset('ela/images/logo-text.png') }}" alt="homepage" class="dark-logo" /></span>
            </a>
        </div>
        <!-- End Logo -->
        <div class="navbar-collapse">
            <!-- toggle and nav items -->
            <ul class="navbar-nav mr-auto mt-md-0">
                <!-- This is  -->
                <li class="nav-item pl-3">
                    @if(\Illuminate\Support\Facades\App::environment('local'))
                        <a href="{{ route('generate.fake') }}" class="btn btn-outline-info" title="Сброс бд: заново выполнение миграций и заполнение тестовыми данными">Сгененировать тестовые данные</a>
                    @endif
                </li>
                <li class="nav-item m-l-10"></li>
                <!-- Messages -->
                <li class="nav-item dropdown mega-dropdown">

                </li>
                <!-- End Messages -->
            </ul>
            <!-- User profile and search -->
            <ul class="navbar-nav my-lg-0">

                <!-- Search -->
                <li class="nav-item hidden-sm-down search-box"> </li>
                <!-- Comment -->
                <li class="nav-item dropdown">

                </li>
                <!-- End Comment -->
                <!-- Messages -->
                <li class="nav-item dropdown">

                </li>
                <!-- End Messages -->
                <!-- Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->login }}</a>
                    <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                        <ul class="dropdown-user">
                            <li><a href="{{ route('profile.edit') }}"><i class="ti-user"></i> Профиль</a></li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                   document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Выход</a></li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- End header header -->

