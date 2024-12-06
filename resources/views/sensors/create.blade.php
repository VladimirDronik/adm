@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Добавление датчика',
        'links' => [route('sensors.index') => 'Датчики']
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('sensors.index') }}" class="btn btn-success m-b-10 m-l-5">Датчики</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'sensors.store', 'method' => 'post', 'id' => 'sensor_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название:', old('name'), ['required' => true]) }}

                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room'), false, false, []) }}

                        {{ Form::bs_select('type', 'Тип:', $types, old('type'), ['required' => true]) }}

                        <div id="source_div" hidden>
                            {{ Form::bs_select('source', 'Тип источника данных:', $sources, old('source'), ['required' => true]) }}
                        </div>

                        <div id="source_id_div" hidden>
                            {{ Form::bs_autoselect('source_id', 'Источник данных:', [], old('source_id'), false, false, []) }}
                        </div>

                        <div id="connection_div" hidden>
                            {{ Form::bs_select('connection', 'Тип подключения:', [], old('connection'), ['required' => true]) }}
                        </div>

                        <div id="port_div" hidden>
                            {{ Form::bs_autoselect('port', 'Порт:', [], old('port'), false, false, []) }}
                        </div>

                        <div id="sda_div" hidden>
                            {{ Form::bs_autoselect('sda', 'Порт SDA:', [], old('sda'), false, false, []) }}
                        </div>

                        <div id="scl_div" hidden>
                            {{ Form::bs_autoselect('scl', 'Порт SCL:', [], old('scl'), false, false, []) }}
                        </div>

                        <input type="hidden" name="input_source" value="">
                        <input type="hidden" name="input_connection" value="">
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
        function createDevicesSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id) {
                    s += '<option selected value="' + options[i].id + '">' + options[i].description + '</option>';
                } else {
                    s += '<option value="' + options[i].id + '">' + options[i].description + '</option>';
                }
            }
            sel.append(s);
        }

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
            $("#auto_sel_source_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_sda").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_scl").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#sensor_form select[name=type]").change(function() {
                let type = $(this).val();
                var connectionSelect = $('#sensor_form select[name=connection]');
                $('#sensor_form select[name=source]').val('');

                switch (type) {
                    case 'custom':
                        $('#source_div').removeAttr("hidden");
                        $('#sensor_form select[name=source]').removeAttr("readonly");
                        $('#sensor_form select[name=source]').removeAttr("disabled");
                        $('#sensor_form select[name=connection]').removeAttr("readonly");
                        $('#connection_div').attr("hidden", true);
                        $('#sensor_form select[name=connection]').attr("disabled", true);
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        $('#source_id_div').attr("hidden", true);
                        $('#auto_sel_source_id').attr("disabled", true);
                        $('#auto_sel_source_id').removeAttr("required");
                        createSelect('#sensor_form select[name=connection]', {
                            '1w': '1w',
                            '1wbus': '1wbus',
                            'i2c': 'i2c',
                            'adc': 'adc'
                        }, -1);
                        break;
                    case 'ds18b20':
                        $('#source_div').removeAttr("hidden");
                        $('#connection_div').removeAttr("hidden");
                        $('#sensor_form select[name=connection]').removeAttr("readonly");
                        $('#sensor_form select[name=connection]').removeAttr("disabled");
                        $('#sensor_form select[name=source]').attr("disabled", true);
                        $("#sensor_form select[name=source]").attr("readonly", true);
                        $("#sensor_form select[name=source]").val("megad");
                        $("#sensor_form input[name=input_source]").val("megad");
                        $('#source_id_div').removeAttr("hidden");
                        $('#auto_sel_source_id').removeAttr("disabled");
                        $("#auto_sel_source_id").attr("required", true);

                        $.ajax({
                            url: "{{ route('ajax.devices.get') }}",
                            data: {'_token': _token, 'types': ['MegaD-2561', 'Monoblock 14IN/14OUT']},
                            success: function (data) {
                                createDevicesSelect('#auto_sel_source_id', data.devices, -1);
                                $('#auto_sel_source_id').trigger("chosen:updated");
                            }
                        });
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        createSelect('#sensor_form select[name=connection]', {
                            '1w': '1w',
                            '1wbus': '1wbus',
                        }, -1);
                        break;
                    case '':
                        $('#source_div').attr("hidden", true);
                        $('#connection_div').attr("hidden", true);
                        $('#sensor_form select[name=source]').attr("disabled", true);
                        $('#sensor_form select[name=connection]').attr("disabled", true);
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        $('#source_id_div').attr("hidden", true);
                        $('#auto_sel_source_id').attr("disabled", true);
                        $('#auto_sel_source_id').removeAttr("required");
                        break;
                    default:
                        $('#source_div').removeAttr("hidden");
                        $('#connection_div').removeAttr("hidden");
                        $('#sensor_form select[name=source]').attr("disabled", true);
                        $('#sensor_form select[name=connection]').attr("disabled", true);
                        $("#sensor_form select[name=source]").attr("readonly", true);
                        $("#sensor_form select[name=connection]").attr("readonly", true);
                        $("#sensor_form select[name=source]").val("megad");
                        $("#sensor_form select[name=connection]").val("i2c");
                        $("#sensor_form input[name=input_source]").val("megad");
                        $("#sensor_form input[name=input_connection]").val("i2c");
                        $('#source_id_div').removeAttr("hidden");
                        $('#auto_sel_source_id').removeAttr("disabled");
                        $("#auto_sel_source_id").attr("required", true);
                        $('#connection_div').removeAttr("hidden");

                        $.ajax({
                            url: "{{ route('ajax.devices.get') }}",
                            data: {'_token': _token, 'types': ['MegaD-2561', 'Monoblock 14IN/14OUT']},
                            success: function (data) {
                                createDevicesSelect('#auto_sel_source_id', data.devices, -1);
                                $('#auto_sel_source_id').trigger("chosen:updated");
                            }
                        });
                        $('#sda_div').removeAttr("hidden");
                        $('#auto_sel_sda').attr("required", true);
                        $('#scl_div').removeAttr("hidden");
                        $('#auto_sel_scl').attr("required", true);
                        $('#auto_sel_sda').removeAttr("disabled");
                        $('#auto_sel_scl').removeAttr("disabled");
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        createSelect('#sensor_form select[name=connection]', {
                            'i2c': 'i2c',
                        }, 'i2c');
                        break;
                }
            });

            $("#sensor_form select[name=source]").change(function() {
                let source = $(this).val();

                switch (source) {
                    case 'megad':
                        $('#source_id_div').removeAttr("hidden");
                        $('#auto_sel_source_id').removeAttr("disabled");
                        $("#auto_sel_source_id").attr("required", true);
                        $('#connection_div').removeAttr("hidden");
                        $('#sensor_form select[name=connection]').removeAttr("readonly");
                        $('#sensor_form select[name=connection]').removeAttr("disabled");

                        $.ajax({
                            url: "{{ route('ajax.devices.get') }}",
                            data: {'_token': _token, 'types': ['MegaD-2561', 'Monoblock 14IN/14OUT']},
                            success: function (data) {
                                createDevicesSelect('#auto_sel_source_id', data.devices, -1);
                                $('#auto_sel_source_id').trigger("chosen:updated");
                            }
                        });
                        break;
                    case 'modbus':
                        $('#source_id_div').removeAttr("hidden");
                        $('#auto_sel_source_id').removeAttr("disabled");
                        $("#auto_sel_source_id").attr("required", true);
                        $('#connection_div').attr("hidden", true);
                        $('#sensor_form select[name=connection]').attr("disabled", true);
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");

                        $.ajax({
                            url: "{{ route('ajax.mod_bus.slavers.get') }}",
                            data: {'_token': _token},
                            success: function (data) {
                                createSelect('#auto_sel_source_id', data.slavers, -1);
                                $('#auto_sel_source_id').trigger("chosen:updated");
                            }
                        });
                        break;
                    case 'mqtt':
                        $('#source_id_div').attr("hidden", true);
                        $('#auto_sel_source_id').attr("disabled", true);
                        $('#auto_sel_source_id').removeAttr("required");
                        $('#connection_div').attr("hidden", true);
                        $('#sensor_form select[name=connection]').attr("disabled", true);
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        break;
                    case '':
                        $('#source_id_div').attr("hidden", true);
                        $('#auto_sel_source_id').attr("disabled", true);
                        $('#auto_sel_source_id').removeAttr("required");
                        $('#connection_div').attr("hidden", true);
                        $('#sensor_form select[name=connection]').attr("disabled", true);
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        break;
                }
            });

            $("#sensor_form select[name=connection]").change(function() {123
                let connection = $(this).val();

                switch (connection) {
                    case 'i2c':
                        $('#sda_div').removeAttr("hidden");
                        $('#auto_sel_sda').attr("required", true);
                        $('#scl_div').removeAttr("hidden");
                        $('#auto_sel_scl').attr("required", true);
                        $('#auto_sel_sda').removeAttr("disabled");
                        $('#auto_sel_scl').removeAttr("disabled");
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        break;
                    case '1wbus':
                        $('#port_div').removeAttr("hidden");
                        $('#auto_sel_port').attr("required", true);
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        break;
                    case '':
                        $('#port_div').attr("hidden", true);
                        $('#auto_sel_port').removeAttr("required");
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        break;
                    default:
                        $('#port_div').removeAttr("hidden");
                        $('#auto_sel_port').attr("required", true);
                        $('#sda_div').attr("hidden", true);
                        $('#auto_sel_sda').removeAttr("required");
                        $('#scl_div').attr("hidden", true);
                        $('#auto_sel_scl').removeAttr("required");
                        break;
                }
            });

            $("#auto_sel_source_id").chosen().change(function() {
                if ($("#sensor_form select[name=source]").val() == 'megad') {
                    let device_id = $(this).val();
                    $.ajax({
                        url: "{{ route('ajax.devices.objects_ports') }}",
                        data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                        success: function (data) {
                            createPortSelect('#auto_sel_port', data.ports, -1);
                            $('#auto_sel_port').trigger("chosen:updated");

                            createPortSelect('#auto_sel_sda', data.ports, -1);
                                $('#auto_sel_sda').trigger("chosen:updated");

                            createPortSelect('#auto_sel_scl', data.ports, -1);
                                $('#auto_sel_scl').trigger("chosen:updated");
                        }
                    });
                }
            });
        });
    </script>
@endsection
