@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование датчика № '. $sensorObject->id,
        'links' => [route('sensors.index') => 'Датчики'],
        'last_link' => 'Редактирование датчика',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('sensors.index') }}" class="btn btn-success m-b-10 m-l-5">Датчики</a>
                        <a href="{{ route('sensors.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($sensorObject, ['route' => ['sensors.update', $sensorObject->id], 'id' => 'sensor_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название:', old('name', $sensorObject->name), ['required' => true]) }}

                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $sensorSettings->where('name', 'room')->first()?->value), false, false, []) }}

                        {{ Form::bs_simple_text('Тип:', $sensorSettings->where('name', 'type')->first()?->value) }}

                        {{ Form::bs_simple_text('Тип источника данных:', $sensorSettings->where('name', 'source')->first()?->value) }}

                        @if($sensorSettings->where('name', 'source')->first()?->value != 'mqtt')
                            {{ Form::bs_autoselect('source_id', 'Источник данных:', $sources, old('source_id', $sensorSettings->where('name', 'source_id')->first()?->value), false, false, ['required' => true]) }}
                        @endif

                        @if($sensorSettings->where('name', 'source')->first()?->value == 'megad')
                            {{ Form::bs_simple_text('Тип подключения:', $sensorSettings->where('name', 'connection')->first()?->value) }}

                            @if($sensorSettings->where('name', 'connection')->first()?->value != 'i2c')
                                {{ Form::bs_autoselect('port', 'Порт:', [], old('port', $sensorSettings->where('name', 'port')->first()?->value), false, false, ['required' => true]) }}
                            @else
                                {{ Form::bs_autoselect('sda', 'Порт SDA:', [], old('sda', $sensorSettings->where('name', 'sda')->first()?->value), false, false, ['required' => true]) }}

                                {{ Form::bs_autoselect('scl', 'Порт SCL:', [], old('scl', $sensorSettings->where('name', 'scl')->first()?->value), false, false, ['required' => true]) }}
                            @endif
                        @endif

                        {{ Form::bs_title('Параметры') }}
                        <div class="form-group row">
                            <label class="col-md-3"><i>Название</i></label>
                            <div class="col-md-3"><i>Значение</i></div>
                            <div class="col-md-3"><i>Время последнего значения</i></div>
                            <div class="col-md-2 text-right"></div>
                        </div>
                        <div id="sensorsParams_div">
                            @foreach($sensorObject->sensorsParams as $sensorsParam)
                                <div class="form-group row" id="divSensorsParam{{$sensorsParam->id}}">
                                    <label class="col-md-3" id="type{{$sensorsParam->id}}">
                                        {{ $sensorsParam->name }}
                                    </label>
                                    <div class="col-md-3" id="value{{$sensorsParam->id}}">
                                        {{ $sensorsParam->value ? ($sensorsParam->value . ' ' . $sensorsParam->unit_name) : '' }}
                                    </div>
                                    <div class="col-md-3" id="timestamp{{$sensorsParam->id}}">
                                        {{ $sensorsParam->timestamp }}
                                    </div>
                                    <div class="col-md-2 text-right">
                                        @if($sensorsParam->graph)
                                            <button type="button" data-id="{{ $sensorsParam->id }}" data-name="{{ $sensorsParam->name }}" class="btn btn-info btn-sm btn-rounded graph_btn">
                                                <i class="fa fa-bar-chart"></i>
                                            </button>
                                        @endif
                                        <button type="button"
                                            data-id="{{ $sensorsParam->id }}"
                                            data-name="{{ $sensorsParam->name }}"
                                            data-param="{{ $sensorsParam->param }}"
                                            data-param_name="{{ $sensorsParam->param_name }}"
                                            data-get_param="{{ $sensorsParam->get_param }}"
                                            data-value="{{ $sensorsParam->value }}"
                                            data-unit_name="{{ $sensorsParam->unit_name }}"
                                            data-units="{{ $sensorsParam->units }}"
                                            data-accuracy="{{ $sensorsParam->accuracy }}"
                                            data-graph="{{ $sensorsParam->graph }}"
                                            data-min_range="{{ $sensorsParam->min_range }}"
                                            data-max_range="{{ $sensorsParam->max_range }}"
                                            data-min_alarm="{{ $sensorsParam->min_alarm }}"
                                            data-max_alarm="{{ $sensorsParam->max_alarm }}"
                                            data-timestamp="{{ $sensorsParam->timestamp }}"
                                            class="btn btn-info btn-sm btn-rounded edit_btn">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </button>
                                        @if($sensorSettings->where('name', 'type')->first()?->value == 'custom' || $sensorSettings->where('name', 'connection')->first()?->value == '1wbus')
                                            <button type="button" data-id="{{ $sensorsParam->id }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 text-left">
                                @if($sensorSettings->where('name', 'type')->first()?->value == 'custom')
                                    <button id="add_btn" type="button" class="btn btn-primary">
                                        <i class="fa fa-plus fa-lg"></i> Добавить параметр
                                    </button>
                                @endif

                                @if($sensorSettings->where('name', 'connection')->first()?->value == '1wbus' && $addressParamsCount !== null && $addressParamsCount < 7)
                                    <button id="add_address_btn" type="button" class="btn btn-primary">
                                        <i class="fa fa-plus fa-lg"></i> Добавить адрес
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#param_modal">&nbsp;</button>
                <button type="button" id="init_info_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="address_init_btn" style="display: none;" data-toggle="modal" data-target="#address_param_modal">&nbsp;</button>
                <button type="button" id="graph_init_btn" style="display: none;" data-toggle="modal" data-target="#graph_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.load_modal')
    @include('sensors.params_modal')
    @include('sensors.address_params_modal')
    @include('components.del_modal')
    @include('sensors.graph_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/core.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/charts.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/themes/animated.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/lang/ru_RU.js') }}"></script>
    <script>
        $("#auto_sel_source_id").chosen({width:"100%", no_results_text: "Не найдено"});

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

        if ("{{ $sensorSettings->where('name', 'source')->first()?->value }}" == 'megad') {
            $.ajax({
                url: "{{ route('ajax.devices.objects_ports') }}",
                data: {'_token': _token, 'device_id': $("#auto_sel_source_id").chosen().val(), 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                success: function (data) {
                    createPortSelect('#auto_sel_port', data.ports, "{{ $sensorSettings->where('name', 'port')->first()?->value ?: -1 }}");
                    $('#auto_sel_port').trigger("chosen:updated");

                    createPortSelect('#auto_sel_sda', data.ports, "{{ $sensorSettings->where('name', 'sda')->first()?->value ?: -1 }}");
                        $('#auto_sel_sda').trigger("chosen:updated");

                    createPortSelect('#auto_sel_scl', data.ports, "{{ $sensorSettings->where('name', 'scl')->first()?->value ?: -1 }}");
                        $('#auto_sel_scl').trigger("chosen:updated");
                }
            });
        }

        $(document).ready(function () {
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_sda").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_scl").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_source_id").chosen().change(function() {
                if ("{{ $sensorSettings->where('name', 'source')->first()?->value }}" == 'megad') {
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

            function showModalError(message) {
                $('#m_error_text').text(message);
                $('#m_error_div').show();
            }

            function clearModal() {
                $('#param_id').val('');
                $('#param_name').val('');
                $('select[name=param_param]').val('');
                $('#param_get_param').val('');
                $('#param_value').val('');
                $('select[name=param_units]').val('');
                $('#param_accuracy').val('');
                $('input[type=radio][name=param_accuracy]').each(function() {
                    $(this).prop('checked', false);
                    $(this).closest('label').removeClass('active');
                });
                $('#param_graph').find('input:checkbox').prop('checked', false);
                $('#param_min_range').val('');
                $('#param_max_range').val('');
                $('#param_min_alarm').val('');
                $('#param_max_alarm').val('');
                $('#param_timestamp').val('');
            }

            function clearAddressModal() {
                $('#address_param_address').val('');
            }

            function showAddModal() {
                clearModal();

                if ("{{ $sensorSettings->where('name', 'type')->first()?->value }}" == 'custom') {
                    $('#param_value_div').prop('hidden', true);
                }

                if ("{{ $sensorSettings->where('name', 'connection')->first()?->value }}" == '1wbus') {
                    $('#param_param_select_div').removeAttr('hidden');
                    $('#param_param_input_div').prop('hidden', true);
                    $('#param_value').prop('readonly', false);
                    $('#param_min_range').prop('readonly', false);
                    $('#param_max_range').prop('readonly', false);
                }

                $('#div_param_timestamp').attr('hidden', true);
                $('#param_modal_title').text('Добавление параметра');
                $('#apply_btn').text('Добавить параметр');
                init_btn.click();
            }

            function showAddAddressModal() {
                clearAddressModal();
                address_init_btn.click();
            }

            function getModalData() {
                let data = {};

                data.id = $('#param_id').val();
                data.name = $('#param_name').val();
                data.param = $('select[name=param_param]').val();
                data.get_param = $('#param_get_param').val();
                data.value = $('#param_value').val();

                if ("{{ $sensorSettings->where('name', 'connection')->first()?->value }}" == '1wbus') {
                    data.units = $('#param_units').val();
                } else {
                    data.units = $('select[name=param_units]').val();
                }

                data.accuracy = $('input[name=param_accuracy]:checked').val();
                data.graph = $("input[name=param_graph]:checked").val();
                data.min_range = $('#param_min_range').val();
                data.max_range = $('#param_max_range').val();
                data.min_alarm = $('#param_min_alarm').val();
                data.max_alarm = $('#param_max_alarm').val();
                data.timestamp = $('#param_timestamp').val();

                return data;
            }

            function getAddressModalData() {
                let data = {};

                data.address = $('#address_param_address').val();

                return data;
            }

            function validateParam(data) {
                if (data.param === '' || data.param === null) {
                    return 'Не указан измеряемый параметр';
                }

                if (data.name === '') {
                    return 'Не указано название';
                }

                if (typeof data.accuracy === 'undefined') {
                    return 'Не указана точность';
                }

                return '';
            }

            function validateAddressParam(data) {
                if (data.address === '') {
                    return 'Не указан адрес';
                }

                return '';
            }

            function showEditModal(data) {
                clearModal();

                if ("{{ $sensorSettings->where('name', 'type')->first()?->value }}" == 'custom') {
                    $('#param_value').prop('readonly', true);
                }

                if ("{{ $sensorSettings->where('name', 'connection')->first()?->value }}" == '1wbus') {
                    $('#param_param_select_div').prop('hidden', true);
                    $('#param_param_input_div').removeAttr('hidden');
                    $('#param_value').prop('readonly', true);
                    $('#param_min_range').prop('readonly', true);
                    $('#param_max_range').prop('readonly', true);
                    $('#param_param_input').val(data.param_name);
                }

                $('#param_id').val(data.id);
                $('#param_name').val(data.name);
                $('select[name=param_param]').val(data.param);
                $('#param_param').val(data.param_name);
                $('#param_get_param').val(data.get_param);
                $('#param_value').val(data.value);
                $('select[name=param_units]').val(data.units);
                $('#param_units').val(data.unit_name);
                $('#param_accuracy').val(data.accuracy);
                $('input[type=radio][name=param_accuracy]').each(function() {
                    if ($(this).val() === data.accuracy) {
                        $(this).prop('checked', true);
                        $(this).closest('label').addClass('active');
                    } else {
                        $(this).prop('checked', false);
                        $(this).closest('label').removeClass('active');
                    }
                });

                if (data.graph == '1') {
                    $('#param_graph').prop('checked', true);
                } else {
                    $('#param_graph').prop('checked', false);
                }

                $('#param_min_range').val(data.min_range);
                $('#param_max_range').val(data.max_range);
                $('#param_min_alarm').val(data.min_alarm);
                $('#param_max_alarm').val(data.max_alarm);
                $('#param_timestamp').val(data.timestamp);
                $('#div_param_timestamp').attr('hidden', false);

                $('#param_modal_title').text('Данные параметра');
                $('#apply_btn').text('Сохранить изменения');

                init_btn.click();
            }

            $('#add_btn').click(showAddModal);
            $('#add_address_btn').click(showAddAddressModal);

            $('#apply_btn').click(function() {
                let data = getModalData();
                let message = validateParam(data);

                if (message !== '') {
                    showModalError(message);
                    return false;
                }

                data.object_id = "{{ $sensorObject->id }}"

                $.ajax({
                    url: "{{ route('ajax.objects.sensor.add_param') }}",
                    data: {'_token': _token, 'data': data},
                    success: function (resp) {
                        if (resp.result) {
                            location.reload();
                        }
                    },
                    error: function () {
                        $('#cancel_btn').click();
                        showErrorModal('Сервер временно недоступен');
                    }
                });
            });

            $('#address_apply_btn').click(function() {
                let data = getAddressModalData();
                let message = validateAddressParam(data);

                if (message !== '') {
                    showModalError(message);
                    return false;
                }

                data.object_id = "{{ $sensorObject->id }}"

                $.ajax({
                    url: "{{ route('ajax.objects.sensor.add_address_param') }}",
                    data: {'_token': _token, 'data': data},
                    success: function (resp) {
                        if (resp.result) {
                            location.reload();
                        }
                    },
                    error: function () {
                        $('#cancel_btn').click();
                        showErrorModal('Сервер временно недоступен');
                    }
                });
            });

            $('body').on('click', '.edit_btn', function() {
                let data = {};

                data.id = $(this).attr('data-id');
                data.name = $(this).attr('data-name');
                data.param = $(this).attr('data-param');
                data.param_name = $(this).attr('data-param_name');
                data.get_param = $(this).attr('data-get_param');
                data.value = $(this).attr('data-value');
                data.units = $(this).attr('data-units');
                data.unit_name = $(this).attr('data-unit_name');
                data.accuracy = $(this).attr('data-accuracy');
                data.graph = $(this).attr('data-graph');
                data.min_range = $(this).attr('data-min_range');
                data.max_range = $(this).attr('data-max_range');
                data.min_alarm = $(this).attr('data-min_alarm');
                data.max_alarm = $(this).attr('data-max_alarm');
                data.timestamp = $(this).attr('data-timestamp');

                showEditModal(data);
            });

            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить параметр?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function() {
                $('#del_cancel_btn').click();
                if (del_id) {
                    $.ajax({
                        url: "{{ route('ajax.objects.sensor.delete_param') }}",
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#divSensorsParam'+del_id).remove();
                            } else {
                                showErrorModal('Ошибка при удалении параметра');
                            }
                        }
                    });
                }
            });

            function createAmChart(id, dates, values, unit_name) {
                // Create chart
                var chart = am4core.create("chart", am4charts.XYChart);
                chart.paddingRight = 20;
                chart.language.locale = am4lang_ru_RU;
                chart.data = getChartData(dates, values);

                var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                dateAxis.baseInterval = {
                    "timeUnit": "minute",
                    "count": 1
                };
                dateAxis.tooltipDateFormat = "dd.MM.yyyy HH:mm";

                var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                valueAxis.tooltip.disabled = true;
                valueAxis.title.text = unit_name;

                var series = chart.series.push(new am4charts.LineSeries());
                series.dataFields.dateX = "date";
                series.dataFields.valueY = "temp";
                series.tooltipText = "T: [bold]{valueY}[/]";
                series.fillOpacity = 0.3;

                chart.cursor = new am4charts.XYCursor();
                chart.cursor.lineY.opacity = 0;
                chart.scrollbarX = new am4charts.XYChartScrollbar();
                chart.scrollbarX.series.push(series);

                chart.events.on("datavalidated", function () {
                    dateAxis.zoom({start:0, end:1});
                });
            }

            function getChartData(dates, values) {
                var chartData = [];
                for (var i = 0; i < dates.length; i++) {
                    chartData.push({
                        date: new Date(dates[i]),
                        temp: values[i]
                    });
                }
                return chartData;
            }

            am4core.ready(function() {
                am4core.useTheme(am4themes_animated);
            });

            function updateChart(sensor_param_id, data) {
                createAmChart(sensor_param_id, data.dates, data.values, data.unit_name);
            }

            function getChartPeriodData(sensor_param_id, period) {
                $.ajax({
                    url: "{{ route('ajax.graphs.sensors_params.period.data') }}",
                    data: {'_token': _token, 'sensor_param_id': sensor_param_id, 'period': period},
                    success: function (resp) {
                        if (resp.result) {
                            updateChart(sensor_param_id, resp.data);
                        }
                    }
                });
            }

            $('body').on('change', '.select_period', function() {
                let sensor_param_id = $(this).attr('data-id');
                let period = $(this).val();
                getChartPeriodData(sensor_param_id, period);
            });

            function showGraphModal(data) {
                $('#graph_modal_title').text('График параметра: ' + data.name);
                $('#select_period').attr('data-id', data.id);
                $('#select_period').change();

                graph_init_btn.click();
            }

            $('body').on('click', '.graph_btn', function() {
                let data = {};

                data.id = $(this).attr('data-id');
                data.name = $(this).attr('data-name');

                showGraphModal(data);
            });
        });
    </script>
@endsection
