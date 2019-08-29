@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление термостата', 'links' => [ route('termostats.index') => 'Термостаты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('termostats.index') }}" class="btn btn-success m-b-10 m-l-5">Список термостатов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'termostats.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('id_termometr', 'Код*:', null, ['required' => true], 'Например, ff750c311703') }}
                            {{ Form::bs_number('optimal', 'Оптимальная температура*:', null, ['min' => 0, 'max' => 40, 'required' => true],
                                'Температура, которая должна быть в помещении') }}
                            {{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 1), ['min' => 0, 'max' => 10, 'required' => true]) }}
                            {{ Form::bs_radio('thermostat', 'Режим*:', $types, old('thermostat', -1), ['required' => true]) }}

                            {{ Form::bs_number('min_threshold', 'Минимальная температура*:', old('min_threshold', 0), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}
                            {{ Form::bs_number('max_threshold', 'Максимальная температура*:', old('max_threshold', 30), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}
                            {{ Form::bs_number('min_alarm', 'Мин. аварийная температура*:', old('min_alarm', 0), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}
                            {{ Form::bs_number('max_alarm', 'Макс. аварийная температура*:', old('max_alarm', 40), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}

                            {{ Form::bs_autoselect('id_object', 'Объект термостата*:', $objects, old('id_object'),
                                false, false, ['required' => true]) }}
                            {{ Form::bs_autoselect('object', 'Объект влияния*:', $objects, old('object'),
                                false, false, ['required' => true], null, 'Объект, у которого меняем состояние') }}

                            {{ Form::bs_autoselect('method_on', 'Метод при включении*:', $methods, old('method_on'),
                                false, false, ['required' => true], null, 'Метод объекта при срабатывании термостата на включение') }}
                            {{ Form::bs_autoselect('method_off', 'Метод при выключении*:', $methods, old('method_off'),
                                false, false, ['required' => true], null, 'Метод объекта при срабатывании термостата на выключение') }}

                            {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}

                            {{ Form::bs_autoselect('id_device', 'Устройство*:', $devices, old('id_device'),
                                false, false, ['required' => true]) }}
                            {{ Form::bs_autoselect('port', 'Номер порта*:', [], null, false, false, ['required' => true]) }}
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
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_on").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_off").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_device").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port").chosen({width:"100%", no_results_text: "Не найдено"});

            function createSelect(target, options, selected) {
                let sel = $(target);
                sel.html('');
                let s = '<option value="">Не выбрано</option>';
                for (let i = 0; i < options.length; i++) {
                    if(selected == options[i])
                        s += '<option selected value="' + options[i] + '">' + options[i] + '</option>';
                    else
                        s += '<option value="' + options[i] + '">' + options[i] + '</option>';
                }
                sel.append(s);
            }


            $("#auto_sel_id_device").chosen().change(function() {
                let device_id = $(this).val();

                $.ajax({
                    url: '{{ route('ajax.devices.ports') }}',
                    data: {'_token': _token, 'device_id': device_id},
                    success: function (data) {
                        createSelect('#auto_sel_port', data.ports, -1);
                        $('#auto_sel_port').trigger("chosen:updated");
                    }
                });
            });
        });
    </script>
@endsection
