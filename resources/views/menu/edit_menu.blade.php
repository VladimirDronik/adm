@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Настройки пункта меню № '. $menu->id .' «'. $menu->name .'»',
        'links' => [ route('menu.index') => 'Помещения'],
        'last_link' => 'Настройки помещения'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('menu.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок меню</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($menu, ['route' => ['menu.update', $menu->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_title('Основные настройки') }}

                        {{ Form::bs_text('title', 'Название*:', $menu->title, ['required' => true]) }}
                        {{ Form::bs_text('link', 'Ссылка*:', $menu->link, ['required' => true]) }}

                        {{ Form::bs_select('parent', 'Группа:', ["0" => "Без группы"] + $groups) }}



                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
