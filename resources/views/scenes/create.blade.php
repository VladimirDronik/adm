@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Добавление объекта</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('objects.index') }}">Объекты</a></li>
                <li class="breadcrumb-item active">Добавление</li>
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
                        <a href="{{ route('objects.index') }}" class="btn btn-success m-b-10 m-l-5">Список объектов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'objects.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_radio('type', 'Тип элемента*:', $types, null, ['required' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_select('view', 'Отображение:', ["" => "Не указано"] + $views) }}

                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
            </div>
        </div>
    </div>
@endsection
