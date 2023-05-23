@extends('layouts._layout')
@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Главная</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Главная</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        @can('devices.controllers')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-building f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('devices.index') }}" class="float-left">Контроллеры</a> {{ $counts['devices'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('devices.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('objects')
            <!-- <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-cube f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('objects.index') }}" class="float-left">Объекты</a> {{ $counts['objects'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('objects.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div> -->
        @endcan
        @can('rooms')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-home f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('rooms.index') }}" class="float-left">Помещения</a> {{ $counts['rooms'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('rooms.index') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('views')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-object-group f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('views.index') }}" class="float-left">Отображения</a> {{ $counts['views'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('views.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('scenes')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-image f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('scenes.index') }}" class="float-left">Сцены</a> {{ $counts['scenes'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('scenes.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('devices.sensors')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-tasks f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('termostats.index') }}" class="float-left">Термостаты</a> {{ $counts['termostats'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('termostats.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('scheduler')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-calendar f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('scheduler.index') }}" class="float-left">Планировщик</a> {{ $counts['scheduler'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('scheduler.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('scripts')
            <div class="col-md-3">
                <div class="card p-30">
                    <div class="media">
                        <div class="media-left meida media-middle">
                            <span><i class="fa fa-flash f-s-40 color-primary"></i></span>
                        </div>
                        <div class="media-body media-text-right">
                            <h2 class="float-none p-l-10"><a href="{{ route('scripts.index') }}" class="float-left">Скрипты</a> {{ $counts['scripts'] }}</h2>
                            <div class="m-b-0 float-none"><a href="{{ route('scripts.create') }}" class="btn btn-sm btn-outline-info btn-outline">Добавить</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
</div>
@endsection







