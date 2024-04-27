@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование I2C датчика № '. $usensor->id_object,
        'links' => [ route('usensors.index') => 'I2C датчики'],
        'last_link' => 'Редактирование I2C датчика'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('usensors.index') }}" class="btn btn-success m-b-10 m-l-5">Список I2C датчиков</a>
                        <a href="{{ route('usensors.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить I2C датчик</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($usensor, ['route' => ['usensors.update', $usensor->id], 'id' => 'usensor_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_simple_text('ID объекта:', $usensor->id_object) }}

                            {{ Form::bs_simple_text('Тип:', $usensor->type_name) }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            {{ Form::bs_autoselect('device_id', 'Контроллер*:', $devices, old('device_id', $usensor->device_id), false, false, [], null) }}

                            {{ Form::bs_autoselect('port_SCL', 'Порт SCL*:', $ports, old('SCL', $usensor->port_SCL), false, false, [], null) }}

                            {{ Form::bs_autoselect('port_SDA', 'Порт SDA*:', $ports, old('SDA', $usensor->port_SDA), false, false, [], null) }}

                            {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room', $usensor->room), false, false, [], null) }}

                            @switch($usensor->type)
                                @case(\App\Models\Usensor::TYPE_BH1750)
                                    {{ Form::bs_simple_text('Освещение:', $usensor->lux ? $usensor->lux . ' ед.' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_HTU21D)
                                    {{ Form::bs_simple_text('Температура:', $usensor->temp ? $usensor->temp . ' °С' : '') }}
                                    {{ Form::bs_simple_text('Влажность:', $usensor->hum ? $usensor->hum . ' %' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_BME280)
                                    {{ Form::bs_simple_text('Температура:', $usensor->temp ? $usensor->temp . ' °С' : '') }}
                                    {{ Form::bs_simple_text('Влажность:', $usensor->hum ? $usensor->hum . ' %' : '') }}
                                    {{ Form::bs_simple_text('Атмосферное давление:', $usensor->atm_pressure ? $usensor->atm_pressure . ' мм рт.ст.' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_OUTDOORV2)
                                    {{ Form::bs_simple_text('Температура:', $usensor->temp ? $usensor->temp . ' °С' : '') }}
                                    {{ Form::bs_simple_text('Влажность:', $usensor->hum ? $usensor->hum . ' %' : '') }}
                                    {{ Form::bs_simple_text('Освещение:', $usensor->lux ? $usensor->lux . ' ед.' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_OUTDOORV3)
                                    {{ Form::bs_simple_text('Температура:', $usensor->temp ? $usensor->temp . ' °С' : '') }}
                                    {{ Form::bs_simple_text('Влажность:', $usensor->hum ? $usensor->hum . ' %' : '') }}
                                    {{ Form::bs_simple_text('Освещение:', $usensor->lux ? $usensor->lux . ' ед.' : '') }}
                                    {{ Form::bs_simple_text('Атмосферное давление:', $usensor->atm_pressure ? $usensor->atm_pressure . ' мм рт.ст.' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_SCD40)
                                    {{ Form::bs_simple_text('Температура:', $usensor->temp ? $usensor->temp . ' °С' : '') }}
                                    {{ Form::bs_simple_text('Влажность:', $usensor->hum ? $usensor->hum . ' %' : '') }}
                                    {{ Form::bs_simple_text('CO2:', $usensor->co2 ? $usensor->co2 . ' ppm' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_SCD41)
                                    {{ Form::bs_simple_text('Температура:', $usensor->temp ? $usensor->temp . ' °С' : '') }}
                                    {{ Form::bs_simple_text('Влажность:', $usensor->hum ? $usensor->hum . ' %' : '') }}
                                    {{ Form::bs_simple_text('CO2:', $usensor->co2 ? $usensor->co2 . ' ppm' : '') }}
                                    @break
                                @case(\App\Models\Usensor::TYPE_PTSENSOR)
                                    {{ Form::bs_simple_text('Давление:', $usensor->pressure ? $usensor->pressure . ' мм рт.ст.' : '') }}
                                    @break
                            @endswitch
                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';

        function createPortSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id) {
                    s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                } else {
                    s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
                }
            }
            sel.append(s);
        }

        $(document).ready(function () {
            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SCL").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SDA").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        createPortSelect('#auto_sel_port_SCL', data.ports, -1);
                        $('#auto_sel_port_SCL').trigger("chosen:updated");

                        createPortSelect('#auto_sel_port_SDA', data.ports, -1);
                        $('#auto_sel_port_SDA').trigger("chosen:updated");
                    }
                });
            });
        });
    </script>
@endsection
