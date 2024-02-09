@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление лампы', 'links' => [ route('illumination.index') => 'Список устройств освещения']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('illumination.index') }}" class="btn btn-success m-b-10 m-l-5">Список устройств освещения</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'lamps.store', 'method' => 'post', 'id' => 'lamp_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            {{ Form::bs_radio('gateway_type', 'Тип подключения*:', $gatewayTypes, old('gateway_type', 'modbus'), ['required' => true]) }}

                            <div id='http_div' hidden>
                                {{ Form::bs_autoselect('http_gateway_id', 'Контроллер*:', $devices, old('http_gateway_id'), false, false, ['required' => true], null, null, 3, false, true) }}

                                {{ Form::bs_autoselect('port_id', 'Порт*:', [], old('port_id'), false, false, ['required' => true], null, null, 3, false, true) }}
                            </div>

                            <div id='modbus_div' hidden>
                                {{ Form::bs_autoselect('modbus_gateway_id', 'Устройство*:', $modbusSlavers, old('modbus_gateway_id'), false, false, ['required' => true], null, null, 3, false, true) }}

                                {{ Form::bs_autoselect('register_id', 'Регистр*:', [], old('register_id'), false, false, ['required' => true], null, null, 3, false, true) }}
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
    <script>
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const url_mod_bus_slavers_registers = '{{ route('ajax.mod_bus.slavers.registers') }}';

        $("#auto_sel_modbus_gateway_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_register_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_http_gateway_id").chosen({width:"100%", no_results_text: "Не найдено"});

        if ($('#lamp_form input[name=gateway_type]:checked').val() == 'modbus') {
            $('#modbus_div').removeAttr("hidden");
            $('#auto_sel_modbus_gateway_id').removeAttr("disabled");
            $('#auto_sel_register_id').removeAttr("disabled");

            $('#http_div').attr("hidden", true);
            $('#auto_sel_http_gateway_id').attr("disabled", true);
            $('#auto_sel_port_id').attr("disabled", true);

            if ($("#auto_sel_modbus_gateway_id").chosen().val()) {
                let slaver_id = $("#auto_sel_modbus_gateway_id").chosen().val();
                $.ajax({
                    url: url_mod_bus_slavers_registers,
                    data: {'slaver_id': slaver_id},
                    success: function (data) {
                        createRegisterSelect('#auto_sel_register_id', data, 0);
                        $('#auto_sel_register_id').trigger("chosen:updated");
                    }
                });
            }
        } else {
            $('#http_div').removeAttr("hidden");
            $('#auto_sel_http_gateway_id').removeAttr("disabled");
            $('#auto_sel_port_id').removeAttr("disabled");

            $('#modbus_div').attr("hidden", true);
            $('#auto_sel_modbus_gateway_id').attr("disabled", true);
            $('#auto_sel_register_id').attr("disabled", true);
        }

        function createRegisterSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '';

            $.each(options, function(key, value) {
                if (selected == key)
                    s += '<option selected value="' + key + '">' + value + '</option>';
                else
                    s += '<option value="' + key + '">' + value + '</option>';
            });
            sel.append(s);
        }

        $(document).ready(function () {
            $('#lamp_form input[name=gateway_type]').change(function() {
                var options = $('#lamp_form input[name=gateway_type]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'modbus') {
                    $('#modbus_div').removeAttr("hidden");
                    $('#auto_sel_modbus_gateway_id').removeAttr("disabled");
                    $('#auto_sel_register_id').removeAttr("disabled");

                    $('#http_div').attr("hidden", true);
                    $('#auto_sel_http_gateway_id').attr("disabled", true);
                    $('#auto_sel_port_id').attr("disabled", true);

                    if ($("#auto_sel_modbus_gateway_id").chosen().val()) {
                        let slaver_id = $("#auto_sel_modbus_gateway_id").chosen().val();
                        $.ajax({
                            url: url_mod_bus_slavers_registers,
                            data: {'slaver_id': slaver_id},
                            success: function (data) {
                                createRegisterSelect('#auto_sel_register_id', data, 0);
                                $('#auto_sel_register_id').trigger("chosen:updated");
                            }
                        });
                    }
                } else {
                    $('#http_div').removeAttr("hidden");
                    $('#auto_sel_http_gateway_id').removeAttr("disabled");
                    $('#auto_sel_port_id').removeAttr("disabled");

                    $('#modbus_div').attr("hidden", true);
                    $('#auto_sel_modbus_gateway_id').attr("disabled", true);
                    $('#auto_sel_register_id').attr("disabled", true);

                    if ($("#auto_sel_http_gateway_id").chosen().val()) {
                        let device_id = $("#auto_sel_http_gateway_id").chosen().val();
                        $.ajax({
                            url: url_ports,
                            data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'switch, socket'},
                            success: function (data) {
                                createPortSelect('#auto_sel_port_id', data.ports, -1);
                                $('#auto_sel_port_id').trigger("chosen:updated");
                            }
                        });
                    }
                }
            });

            $("#auto_sel_http_gateway_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'switch, socket'},
                    success: function (data) {
                        createPortSelect('#auto_sel_port_id', data.ports, 0);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_modbus_gateway_id").chosen().change(function() {
                $.ajax({
                    url: url_mod_bus_slavers_registers,
                    data: {'slaver_id': $(this).val()},
                    success: function (data) {
                        createRegisterSelect('#auto_sel_register_id', data, 0);
                        $('#auto_sel_register_id').trigger("chosen:updated");
                    }
                });
            });

            function createPortSelect(target, options, selected) {
                let sel = $(target);
                sel.html('');
                let s = '';
                for (let i = 0; i < options.length; i++) {
                    if (selected == options[i].id)
                        s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                    else
                        s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
                }
                sel.append(s);
            }
        });
    </script>
@endsection
