@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование регулятора № '. $regulator->object_id,
        'links' => [ route('regulators.index') => 'Регуляторы'],
        'last_link' => 'Редактирование регулятора',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('regulators.index') }}" class="btn btn-success m-b-10 m-l-5">Список регуляторов</a>
                        <a href="{{ route('regulators.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить регулятор</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($regulator, ['route' => ['regulators.update', $regulator->id], 'id' => 'regulator_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}

                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_simple_text('ID объекта:', $regulator->object_id) }}

                            {{ Form::bs_text('name', 'Название*:', old('name', $regulator->object->name), ['required' => true]) }}

                            {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room', $regulator->room), false, false, [], null) }}

                            {{ Form::bs_radio('status', 'Состояне:', ['on' => 'Вкл', 'off' => 'Выкл'], old('status', $regulator->object->status), []) }}

                            @if(!$regulator->source)
                                {{ Form::bs_text('setpoint', 'Уставка*:', old('setpoint', $regulator->setpoint), ['required' => true]) }}

                                {{ Form::bs_text('hysteresis', 'Гистерезис*:', old('hysteresis', $regulator->hysteresis), ['required' => true]) }}

                                <br>
                                <br>
                                {{ Form::bs_title('Метод при значении выше уставки') }}

                                {{ Form::bs_autoselect('higher_object', 'Объект:', $objects, $regulator->higherMethod->id_object, false, false, [], null) }}

                                {{ Form::bs_autoselect('higher_method', 'Метод:', $higherMethods, $regulator->higher_method, false, false, [], null) }}

                                <div class="form-group row" id="higher_method_params_div" @if(!$regulator->higher_method_params) style="display: none;" @endif>
                                    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="higher_method_params"></label>
                                    <div class="col-md-9 pr-0">
                                        <div class="form-group row ">
                                            <label class="control-label text-right col-md-6 label-fix" id="higher_method_params_label" for="higher_method_params">{{ $regulator->higherMethod->params ?: '...'}}</label>
                                            <div class="col-md-6">
                                                <input class="form-control" autocomplete="off" id="higher_method_params" name="higher_method_params" type="text" value="{{ old('higher_method_params', $regulator->higher_method_params) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <br>
                                <br>
                                {{ Form::bs_title('Метод при значении меньше уставки') }}

                                {{ Form::bs_autoselect('lower_object', 'Объект:', $objects, $regulator->lowerMethod->id_object, false, false, [], null) }}

                                {{ Form::bs_autoselect('lower_method', 'Метод:', $lowerMethods, $regulator->lower_method, false, false, [], null) }}

                                <div class="form-group row" id="lower_method_params_div" @if(!$regulator->lower_method_params) style="display: none;" @endif>
                                    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="lower_method_params"></label>
                                    <div class="col-md-9 pr-0">
                                        <div class="form-group row ">
                                            <label class="control-label text-right col-md-6 label-fix" id="lower_method_params_label" for="lower_method_params">{{ $regulator->lowerMethod->params ?: '...'}}</label>
                                            <div class="col-md-6">
                                                <input class="form-control" autocomplete="off" id="lower_method_params" name="lower_method_params" type="text" value="{{ old('lower_method_params', $regulator->lower_method_params) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <br>
                                <br>
                                {{ Form::bs_title('Аварийный метод') }}

                                {{ Form::bs_autoselect('fallback_object', 'Объект:', $objects, $regulator->fallbackMethod?->id_object, false, false, [], null) }}

                                {{ Form::bs_autoselect('fallback_method', 'Метод:', $fallbackMethods, $regulator->fallback_method, false, false, [], null) }}

                                <div class="form-group row" id="fallback_method_params_div" @if(!$regulator->fallback_method_params) style="display: none;" @endif>
                                    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="fallback_method_params"></label>
                                    <div class="col-md-9 pr-0">
                                        <div class="form-group row ">
                                            <label class="control-label text-right col-md-6 label-fix" id="fallback_method_params_label" for="fallback_method_params">{{ $regulator->fallbackMethod?->params ?: '...'}}</label>
                                            <div class="col-md-6">
                                                <input class="form-control" autocomplete="off" id="fallback_method_params" name="fallback_method_params" type="text" value="{{ old('fallback_method_params', $regulator->fallback_method_params) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <br>
                                <br>
                            @else
                                <input type="text" name="independent_device" hidden value="1">
                                @if($regulator->source == 'modbus')
                                    <input type="text" name="source" hidden value="modbus">
                                    {{ Form::bs_autoselect('modbus_slaver', 'Устройство*:', $slavers, old('modbus_slaver', $regulator->source_id), false, false, [], null) }}

                                    {{ Form::bs_autoselect('modbus_register', 'Регистр*:', [], old('modbus_register'), false, false, [], null) }}
                                @elseif($regulator->source == 'megad')
                                    <input type="text" name="source" hidden value="megad">
                                    {{ Form::bs_autoselect('device', 'Контроллер*:', $devices, old('device', $device->id), false, false, [], null) }}

                                    {{ Form::bs_autoselect('port', 'Порт*:', [], old('port'), false, false, [], null) }}
                                @endif
                            @endif

                            {{ Form::bs_text('min_setpoint', 'Минимальное значение уставки*:', old('min_setpoint', $regulator->min_setpoint), []) }}

                            {{ Form::bs_text('max_setpoint', 'Максимальное значение уставки*:', old('max_setpoint', $regulator->max_setpoint), []) }}
                        </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const url_methods = "{{ route('ajax.objects.methods') }}";

        $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_higher_object").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_higher_method").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_lower_object").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_lower_method").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_fallback_object").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_fallback_method").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_modbus_slaver").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_modbus_register").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_device").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_port").chosen({width:"100%", no_results_text: "Не найдено"});

        function createSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';
            $.each(options, function(key, value) {
                if (selected == key) {
                    s += '<option selected value="' + key + '">' + value + '</option>';
                } else {
                    s += '<option value="' + key + '">' + value + '</option>';
                }
            });
            sel.append(s);
        }

        function createPortSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id)
                    s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                else
                    s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
            }
            sel.append(s);
        }

        $(document).ready(function () {
            $("#auto_sel_device").chosen().change(function() {
                let device_id = $(this).val();
                if (device_id) {
                    $.ajax({
                        url: "{{ route('ajax.devices.objects_ports') }}",
                        data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                        success: function (data) {
                            $("#port_div").removeAttr("hidden");
                            createPortSelect('#auto_sel_port', data.ports, -1);
                            $('#auto_sel_port').trigger("chosen:updated");
                        }
                    });
                }
            });

            if ($("#auto_sel_device").chosen().val()) {
                $.ajax({
                    url: "{{ route('ajax.devices.objects_ports') }}",
                    data: {'_token': _token, 'device_id': $("#auto_sel_device").chosen().val(), 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        $("#port_div").removeAttr("hidden");
                        createPortSelect('#auto_sel_port', data.ports, "{{ old('port', $regulator->source_id) }}");
                        $('#auto_sel_port').trigger("chosen:updated");
                    }
                });
            }

            $("#auto_sel_modbus_slaver").chosen().change(function() {
                let slaver_id = $(this).val();
                if (slaver_id) {
                    $.ajax({
                        url: "{{ route('ajax.mod_bus.slavers.registers') }}",
                        data: {'_token': _token, 'slaver_id': slaver_id},
                        success: function (data) {
                            $("#register_div").removeAttr("hidden");
                            createSelect('#auto_sel_modbus_register', data, -1);
                            $('#auto_sel_modbus_register').trigger("chosen:updated");
                        }
                    });
                }
            });

            if ($("#auto_sel_modbus_slaver").chosen().val()) {
                $.ajax({
                    url: "{{ route('ajax.mod_bus.slavers.registers') }}",
                    data: {'_token': _token, 'slaver_id': $("#auto_sel_modbus_slaver").chosen().val()},
                    success: function (data) {
                        $("#register_div").removeAttr("hidden");
                        createSelect('#auto_sel_modbus_register', data, "{{ old('modbus_register', $regulator->sensorsParam->get_param) }}");
                        $('#auto_sel_modbus_register').trigger("chosen:updated");
                    }
                });
            }

            $("#auto_sel_higher_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('higher_method_params');

                getMethods(object_id, '#auto_sel_higher_method');
            });

            $("#auto_sel_lower_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('lower_method_params');

                getMethods(object_id, '#auto_sel_lower_method');
            });

            $("#auto_sel_fallback_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('fallback_method_params');

                getMethods(object_id, '#auto_sel_fallback_method');
            });

            $("#auto_sel_higher_method").chosen().change(function() {
                loadMethods($(this).val(), 'higher_method_params', '#regulator_form');
            });

            $("#auto_sel_lower_method").chosen().change(function() {
                loadMethods($(this).val(), 'lower_method_params', '#regulator_form');
            });

            $("#auto_sel_fallback_method").chosen().change(function() {
                loadMethods($(this).val(), 'fallback_method_params', '#regulator_form');
            });
        });
    </script>
@endsection
