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
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#regulatorstab1" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Основное</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#regulatorstab3" role="tab">
                                        <span class="hidden-sm-up"><i class="ti-home"></i></span>
                                        <span class="hidden-xs-down">Свойства</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane p-20 active" id="regulatorstab1" role="tabpanel">
                                    @include('regulators/edit_tabs/main')
                                </div>
                                <div class="tab-pane p-20" id="regulatorstab3" role="tabpanel">
                                    @include('regulators/edit_tabs/prop')
                                </div>
                            </div>
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
    <script src="{{ asset('ela/js/pagescripts/service.js') }}"></script>
    <script>
        const url_methods = "{{ route('ajax.objects.methods') }}";

        $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_alice_room").chosen({width:"100%", no_results_text: "Не найдено"});
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
        $("#auto_sel_sensor").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_sensor_param").chosen({width:"100%", no_results_text: "Не найдено"});

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
            serviceInit();

            $("#auto_sel_sensor").chosen().change(function() {
                let sensor_id = $(this).val();
                if (sensor_id) {
                    $.ajax({
                        url: "{{ route('ajax.objects.sensor.get_params') }}",
                        data: {'_token': _token, 'sensor_id': sensor_id,},
                        success: function (data) {
                            createSelect('#auto_sel_sensor_param', data.params, -1);
                            $('#auto_sel_sensor_param').trigger("chosen:updated");
                        }
                    });
                }
            });

            if ($("#auto_sel_sensor").chosen().val()) {
                $.ajax({
                    url: "{{ route('ajax.objects.sensor.get_params') }}",
                    data: {'_token': _token, 'sensor_id': $("#auto_sel_sensor").chosen().val()},
                    success: function (data) {
                        createSelect('#auto_sel_sensor_param', data.params, "{{ old('sensor_param', $regulator->sensors_param_id) }}");
                        $('#auto_sel_sensor_param').trigger("chosen:updated");
                    }
                });
            }

            $("#auto_sel_device").chosen().change(function() {
                let device_id = $(this).val();
                if (device_id) {
                    $.ajax({
                        url: "{{ route('ajax.devices.objects_ports') }}",
                        data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                        success: function (data) {
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
