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

                            @if($sensorSettings->where('name', 'connection')->first()?->value == '1wbus')
                                {{ Form::bs_number('address', 'Адрес:', old('address', $sensorSettings->where('name', 'address')->first()?->value), ['required' => true]) }}
                            @endif
                        @endif

                        {{ Form::bs_title('Параметры') }}
                        <div class="form-group row">
                            <label class="col-md-3"><i>Название</i></label>
                            <div class="col-md-3"><i>Значение</i></div>
                            <div class="col-md-3"><i>Ед. измерения</i></div>
                            <div class="col-md-2 text-right"></div>
                        </div>
                        <div id="sensorsParams_div">
                            @foreach($sensorObject->sensorsParams as $sensorsParam)
                                <div class="form-group row" id="divSensorsParam{{$sensorsParam->id}}">
                                    <label class="col-md-3" id="type{{$sensorsParam->id}}">
                                        {{ $sensorsParam->name }}
                                    </label>
                                    <div class="col-md-3" id="value{{$sensorsParam->id}}">
                                        {{ $sensorsParam->value }}
                                    </div>
                                    <div class="col-md-3" id="units{{$sensorsParam->id}}">
                                        {{ $sensorsParam->units }}
                                    </div>
                                    <div class="col-md-2 text-right">
                                    <button type="button"
                                        data-id="{{ $sensorsParam->id }}"
                                        data-name="{{ $sensorsParam->name }}"
                                        data-param="{{ $sensorsParam->param }}"
                                        data-get_param="{{ $sensorsParam->get_param }}"
                                        data-value="{{ $sensorsParam->value }}"
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
                                        @if($sensorSettings->where('name', 'type')->first()?->value == 'custom')
                                            <button type="button" data-id="{{ $sensorsParam->id }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($sensorSettings->where('name', 'type')->first()?->value == 'custom')
                            <div class="form-group row">
                                <div class="col-md-12 text-left">
                                    <button id="add_btn" type="button" class="btn btn-primary">
                                        <i class="fa fa-plus fa-lg"></i> Добавить параметр
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#param_modal">&nbsp;</button>
                <button type="button" id="init_info_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.load_modal')
    @include('sensors.params_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
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
                $('#param_param').val('');
                $('#param_get_param').val('');
                $('#param_value').val('');
                $('#param_units').val('');
                $('#param_accuracy').val('');
                $('#param_graph').find('input:checkbox').prop('checked', false);
                $('#param_min_range').val('');
                $('#param_max_range').val('');
                $('#param_min_alarm').val('');
                $('#param_max_alarm').val('');
                $('#param_timestamp').val('');
            }

            function showAddModal() {
                clearModal();
                $('#div_param_timestamp').attr('hidden', true);
                $('#param_modal_title').text('Добавление параметра');
                $('#apply_btn').text('Добавить параметр');
                init_btn.click();
            }

            function getModalData() {
                let data = {};

                data.id = $('#param_id').val();
                data.name = $('#param_name').val();
                data.param = $('#param_param').val();
                data.get_param = $('#param_get_param').val();
                data.value = $('#param_value').val();
                data.units = $('#param_units').val();
                data.accuracy = $('#param_accuracy').val();
                data.graph = $("input[name=param_graph]:checked").val();
                data.min_range = $('#param_min_range').val();
                data.max_range = $('#param_max_range').val();
                data.min_alarm = $('#param_min_alarm').val();
                data.max_alarm = $('#param_max_alarm').val();
                data.timestamp = $('#param_timestamp').val();

                return data;
            }

            function validateParam(data) {
                if (data.param === '') {
                    return 'Не указан символьный код';
                }

                if (data.name === '') {
                    return 'Не указано название';
                }

                if (data.accuracy === '') {
                    return 'Не указана точность';
                }

                return '';
            }

            function showEditModal(data) {
                clearModal();

                $('#param_id').val(data.id);
                $('#param_name').val(data.name);
                $('#param_param').val(data.param);
                $('#param_get_param').val(data.get_param);
                $('#param_value').val(data.value);
                $('#param_units').val(data.units);
                $('#param_accuracy').val(data.accuracy);

                if (data.graph == '1') {
                    $('#param_graph_text').val('Да');
                    $('#param_graph').prop('checked', true);
                } else {
                    $('#param_graph_text').val('Нет');
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

            $('body').on('click', '.edit_btn', function() {
                let data = {};

                data.id = $(this).attr('data-id');
                data.name = $(this).attr('data-name');
                data.param = $(this).attr('data-param');
                data.get_param = $(this).attr('data-get_param');
                data.value = $(this).attr('data-value');
                data.units = $(this).attr('data-units');
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
        });
    </script>
@endsection
