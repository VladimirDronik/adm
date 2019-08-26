@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Редактирование отображения № {{ $view->id }}</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('views.index') }}">Отображения</a></li>
                <li class="breadcrumb-item active">Редактирование</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок отображений</a>
                        <a href="{{ route('views.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить отображение</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($view, ['route' => ['views.update', $view->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Тип элемента') }}

                        {{ Form::bs_select('type', 'Тип элемента*:', ["" => "Не указан"] + $types, null, ['required' => true]) }}

                        {{ Form::bs_title('Текст и графика') }}

                        {{ Form::bs_text('on_title_top','Надпись при включении:', null, [], 'Верхняя строка') }}
                        {{ Form::bs_text('on_title_bottom','', null, [], 'Нижняя строка') }}

                        {{ Form::bs_text('off_title_top','Надпись при выключении:', null, [], 'Верхняя строка') }}
                        {{ Form::bs_text('off_title_bottom','', null, [], 'Нижняя строка') }}

                        {{ Form::bs_title('Расположение') }}

                        {{ Form::bs_select('room', 'Помещение*:', ["" => "Не указано"] + $rooms, null, ['required' => true]) }}
                        {{ Form::bs_select('scene', 'Сцена*:', ["" => "Не указана"] + $scenes, null, ['required' => true]) }}
                        {{ Form::bs_number('position_left','Левый отступ (px):') }}
                        {{ Form::bs_number('position_top','Верхний отступ (px):') }}

                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/pagescripts/views.js"></script>
@endsection
