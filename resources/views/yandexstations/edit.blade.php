@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование ЯндексСтанции № '. $yandexstation->id . ' «' . $yandexstation->name .'»',
        'links' => [ route('yandexstations.index') => 'ЯндексСтанции'],
        'last_link' => 'Редактирование ЯндексСтанции'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('yandexstations.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок станций</a>
                        <a href="{{ route('yandexstations.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить станцию</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($yandexstation, ['route' => ['yandexstations.update', $yandexstation->id], 'id' => 'yandexstation_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_text('speaker_id', 'ID станции*:', null, ['required' => true]) }}
                        {{ Form::bs_number('volume', 'Громкость звука:', old('volume', $yandexstation->volume), ['min' => 0, 'max' => 100, 'required' => false],'') }}
                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $yandexstation->room), false, false) }}

                    </div>



                    {{ Form::bs_submit_btn() }}


                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>

            </div>
        </div>
    </div>




@endsection

@section('scripts')

@endsection
