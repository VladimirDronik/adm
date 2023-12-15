@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование шины № '. $bus->id,
        'links' => [ route('mod_bus.buses.index') => 'Шины'],
        'last_link' => 'Редактирование шины'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.buses.index', ['tab' => $bus->type]) }}" class="btn btn-success m-b-10 m-l-5">Шины</a>
                        <a href="{{ route('mod_bus.buses.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить шину</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($bus, ['route' => ['mod_bus.buses.update', $bus->id], 'id' => 'bus_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('Тип:', $bus->type_name) }}

                        @if($bus->type == \App\Models\ModbusBus::TYPE_RTU)
                            {{ Form::bs_simple_text('Устройство:', $bus->device) }}

                            {{ Form::bs_select('baudrate', 'Скорость*:', $baudrates, old('baudrate', $bus->baudrate), ['required' => true]) }}

                            {{ Form::bs_number('length', 'Биты данных*:', old('length', $bus->length), ['min' => 5, 'max' => 8, 'required' => true]) }}

                            {{ Form::bs_select('parity', 'Четность*:', $parities, old('parity', $bus->parity), ['required' => true]) }}

                            {{ Form::bs_select('stopbits', 'Стоповые биты*:', $stopbits, old('stopbits', $bus->stopbits), ['required' => true]) }}
                        @else
                            {{ Form::bs_text('device_text', 'Устройство*:', old('device_text', $bus->device), ['required' => true]) }}

                            {{ Form::bs_text('ip_address', 'IP адрес*:', old('ip_address', $bus->ip_address), ['required' => true]) }}

                            {{ Form::bs_number('port', 'Порт*:', old('port', $bus->port), ['min' => 0, 'max' => 65535, 'required' => true]) }}
                        @endif

                        <input type="hidden" name="type" value="{{ $bus->type }}">
                    </div>

                    {{ Form::bs_submit_btn() }}

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
@endsection
