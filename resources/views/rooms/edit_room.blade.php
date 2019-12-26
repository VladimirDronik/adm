@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Настройки помещения № '. $room->id .' «'. $room->name .'»',
        'links' => [ route('rooms.index') => 'Помещения'],
        'last_link' => 'Настройки помещения'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('rooms.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок помещений</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($room, ['route' => ['rooms.update', $room->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        <h3>Настройки помещения «{{ $room->name }}»</h3>
                        {{ Form::bs_title('Основные настройки') }}

                        {{ Form::bs_select('group_room', 'Группа:', ["0" => "Без группы"] + $groups) }}

                        {{ Form::bs_title('Термостаты') }}

                        @forelse($room->termostats as $termostat)
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="name">
                                    <a href="{{ route('termostats.edit',[$termostat->id]) }}" target="_blank">
                                        {{ $termostat->id_termometr }}</a>
                                </label>
                                <div class="col-md-9">
                                    <p>Текущая температура: {{ $termostat->current }} &#176;С</p>
                                    <p>Оптимальная температура: {{ $termostat->optimal }} &#176;С</p>
                                    <p><a href="{{ route('termostats.edit',[$termostat->id]) }}" target="_blank"><i>Подробнее</i></a></p>
                                </div>
                            </div>
                        @empty
                            Отсутствуют<br><br>
                        @endforelse

                        {{ Form::bs_title('Температурные пресеты') }}

                        {{ Form::bs_number('temperature_normal', 'Обычный режим*:', old('temperature_normal', $room->temperature->normal ?? ''),
                            ['min' => 10, 'max' => 30, 'required' => true],'От 10 до 30') }}
                        {{ Form::bs_number('temperature_night', 'Ночной режим*:', old('temperature_night', $room->temperature->night ?? ''),
                            ['min' => 10, 'max' => 30, 'required' => true],'От 10 до 30') }}
                        {{ Form::bs_number('temperature_eco', 'Экорежим*:', old('temperature_eco', $room->temperature->eco ?? ''),
                            ['min' => 10, 'max' => 30, 'required' => true],'От 10 до 30') }}

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
