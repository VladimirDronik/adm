@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Добавление регулятора',
        'links' => [ route('regulators.index') => 'Регуляторы'],
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('regulators.index') }}" class="btn btn-success m-b-10 m-l-5">Список регуляторов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'regulators.store', 'method' => 'post', 'id' => 'regulator_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                            {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room'), false, false, [], null) }}

                            {{ Form::bs_checkbox('independent_device', 'Является самостоятельным устройством:', old('independent_device')) }}

                            <div id="not_independent_device_div" hidden>
                                {{ Form::bs_autoselect('sensor', 'Датчик*:', $sensors, old('sensor'), false, false, [], null) }}

                                <div id="sensor_param_div" hidden>
                                    {{ Form::bs_autoselect('sensor_param', 'Параметр*:', [], old('sensor_param'), false, false, [], null) }}
                                </div>

                                {{ Form::bs_text('setpoint', 'Уставка*:', old('setpoint'), []) }}

                                {{ Form::bs_text('hysteresis', 'Гистерезис*:', old('hysteresis'), []) }}

                                <br>
                                <br>
                                {{ Form::bs_title('Метод при значении выше уставки') }}

                                {{ Form::bs_autoselect('higher_object', 'Объект:', $objects, null, false, false, [], null) }}

                                {{ Form::bs_autoselect('higher_method', 'Метод:', [], null, false, false, [], null) }}

                                <div class="form-group row" id="higher_method_params_div" style="display: none;">
                                    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="higher_method_params"></label>
                                    <div class="col-md-9 pr-0">
                                        <div class="form-group row ">
                                            <label class="control-label text-right col-md-6 label-fix" id="higher_method_params_label" for="higher_method_params">...</label>
                                            <div class="col-md-6">
                                                <input class="form-control" autocomplete="off" id="higher_method_params" name="higher_method_params" type="text" value="{{ old('higher_method_params') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <br>
                                <br>
                                {{ Form::bs_title('Метод при значении меньше уставки') }}

                                {{ Form::bs_autoselect('lower_object', 'Объект:', $objects, null, false, false, [], null) }}

                                {{ Form::bs_autoselect('lower_method', 'Метод:', [],null, false, false, [], null) }}

                                <div class="form-group row" id="lower_method_params_div" style="display: none;">
                                    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="lower_method_params"></label>
                                    <div class="col-md-9 pr-0">
                                        <div class="form-group row ">
                                            <label class="control-label text-right col-md-6 label-fix" id="lower_method_params_label" for="lower_method_params">...</label>
                                            <div class="col-md-6">
                                                <input class="form-control" autocomplete="off" id="lower_method_params" name="lower_method_params" type="text" value="{{ old('lower_method_params') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <br>
                                <br>
                                {{ Form::bs_title('Аварийный метод') }}

                                {{ Form::bs_autoselect('fallback_object', 'Объект:', $objects, null, false, false, [], null) }}

                                {{ Form::bs_autoselect('fallback_method', 'Метод:', [], null, false, false, [], null) }}

                                <div class="form-group row" id="fallback_method_params_div" style="display: none;">
                                    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="fallback_method_params"></label>
                                    <div class="col-md-9 pr-0">
                                        <div class="form-group row ">
                                            <label class="control-label text-right col-md-6 label-fix" id="fallback_method_params_label" for="fallback_method_params">...</label>
                                            <div class="col-md-6">
                                                <input class="form-control" autocomplete="off" id="fallback_method_params" name="fallback_method_params" type="text" value="{{ old('fallback_method_params') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <div id="independent_device_div" hidden>
                                {{ Form::bs_radio('source', 'Тип источника данных*:', ['modbus' => 'Modbus', 'megad' => 'MegaD'], old('source'), []) }}

                                <div id="modbus_div" hidden>
                                    {{ Form::bs_autoselect('modbus_slaver', 'Устройство*:', $slavers, old('modbus_slaver'), false, false, [], null) }}

                                    <div id="register_div" hidden>
                                        {{ Form::bs_autoselect('modbus_register', 'Регистр*:', [], old('modbus_register'), false, false, [], null) }}
                                    </div>
                                </div>

                                <div id="megad_div" hidden>
                                    {{ Form::bs_autoselect('device', 'Контроллер*:', $devices, old('device'), false, false, [], null) }}

                                    <div id="port_div" hidden>
                                        {{ Form::bs_autoselect('port', 'Порт*:', [], old('port'), false, false, [], null) }}
                                    </div>
                                </div>
                            </div>

                            <br>
                            <br>
                            {{ Form::bs_text('min_setpoint', 'Минимальное значение уставки*:', old('min_setpoint'), []) }}

                            {{ Form::bs_text('max_setpoint', 'Максимальное значение уставки*:', old('max_setpoint'), []) }}
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
        $("#auto_sel_sensor").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_sensor_param").chosen({width:"100%", no_results_text: "Не найдено"});
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

        var sourceOptions = $('#regulator_form input[name=source]');
        for (var i = 0; i < sourceOptions.length; i++) {
            if (sourceOptions[i].checked) {
                var selectedSourceOption = sourceOptions[i].value;
            }
        }

        if (selectedSourceOption == 'modbus') {
            $('#modbus_div').removeAttr("hidden");
            $("#megad_div").attr("hidden", true);
        } else if (selectedSourceOption == 'megad') {
            $('#modbus_div').attr("hidden", true);
            $("#megad_div").removeAttr("hidden");
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
                        createPortSelect('#auto_sel_port', data.ports, "{{ old('port') }}");
                        $('#auto_sel_port').trigger("chosen:updated");
                    }
                });
            }

            $('#regulator_form input[name="independent_device"]').change(function() {
                if ($(this).prop('checked')) {
                    $('#independent_device_div').removeAttr("hidden");
                    $("#not_independent_device_div").attr("hidden", true);
                } else {
                    $('#independent_device_div').attr("hidden", true);
                    $("#not_independent_device_div").removeAttr("hidden");
                }
            });

            if ($('#regulator_form input[name="independent_device"]').prop('checked')) {
                $('#independent_device_div').removeAttr("hidden");
                $("#not_independent_device_div").attr("hidden", true);
            } else {
                $('#independent_device_div').attr("hidden", true);
                $("#not_independent_device_div").removeAttr("hidden");
            }

            $("#auto_sel_sensor").chosen().change(function() {
                let sensor_id = $(this).val();
                if (sensor_id) {
                    $.ajax({
                        url: "{{ route('ajax.objects.sensor.get_params') }}",
                        data: {'_token': _token, 'sensor_id': sensor_id,},
                        success: function (data) {
                            $("#sensor_param_div").removeAttr("hidden");
                            createSelect('#auto_sel_sensor_param', data.params, -1);
                            $('#auto_sel_sensor_param').trigger("chosen:updated");
                        }
                    });
                }
            });

            if ($("#auto_sel_sensor").chosen().val()) {
                $.ajax({
                    url: "{{ route('ajax.objects.sensor.get_params') }}",
                    data: {'_token': _token, 'sensor_id': $("#auto_sel_sensor").chosen().val(),},
                    success: function (data) {
                        $("#sensor_param_div").removeAttr("hidden");
                        createSelect('#auto_sel_sensor_param', data.params, "{{ old('sensor_param') }}");
                        $('#auto_sel_sensor_param').trigger("chosen:updated");
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
                        createSelect('#auto_sel_modbus_register', data, "{{ old('modbus_register') }}");
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

            $('#regulator_form input[name=source]').change(function() {
                var options = $('#regulator_form input[name=source]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'modbus') {
                    $('#modbus_div').removeAttr("hidden");
                    $("#megad_div").attr("hidden", true);
                } else if (selectedOption == 'megad') {
                    $('#modbus_div').attr("hidden", true);
                    $("#megad_div").removeAttr("hidden");
                }
            });
        });
    </script>
@endsection
