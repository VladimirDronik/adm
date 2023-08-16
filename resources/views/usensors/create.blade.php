@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление универсального датчика', 'links' => [ route('usensors.index') => 'Термостаты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('usensors.index') }}" class="btn btn-success m-b-10 m-l-5">Список универсальных датчиков</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'usensors.store', 'method' => 'post',
                            'id' => 'usensor_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            {{ Form::bs_radio('type', 'Тип*:', $types, old('type')) }}

                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                    <strong>Объект универсального датчика*:</strong>
                                </label>
                                <div class="col-sm-9">
                                    <div class="form-group row">
                                        <div class="col-md-12 p-0">
                                            <div class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-success btn-sm active">
                                                    <input type="radio" name="object_type" autocomplete="off" checked value="auto"> Создать автоматически
                                                </label>
                                                @can('devices.create-manual-object')
                                                    <label class="btn btn-success btn-sm">
                                                        <input type="radio" name="object_type" autocomplete="off" value="manual">  Выбор из списка
                                                    </label>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="manual_object_div" style="display: none;">
                                        <div class="col-sm-11 pr-0">
                                            <select autocomplete="off" id="auto_sel_id_object"
                                                    data-placeholder="не выбрано"
                                                    name="id_object"
                                                    class="chosen-select form-control"
                                                    style="width:350px;">
                                                <option value="">Не выбрано</option>
                                                @foreach ($objects as $key => $value)
                                                    <option value="{{ $key }}" @if($key == old('id_object')) selected @endif>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-1 pt-1 text-left">
                                            <button type="button" id="auto_sel_btn_id_object" class="btn btn-default btn-sm" title=" Создать объект ">
                                                <i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="row" id="auto_object_div">
                                        <div class="col-sm-12 pr-0">
                                            <p>
                                                При создании универсального датчика будет создан объект с таким же названием.
                                                У объекта будет создан метод «Проверка датчика».
                                                К методу будет привязан системный скрипт «Проверка датчика».
                                            </p>
                                        </div>

                                    </div>

                                </div>
                                <div class="col-sm-12 pr-0 mt-4">
                                    {{ Form::bs_autoselect('device_id', 'Контроллер*:', $devices, old('device_id'),
                                       false, false, [], null) }}

                                    {{ Form::bs_autoselect('port_SCL', 'Порт SCL*:', [], old('SCL'),
                                        false, false, [], null) }}

                                    {{ Form::bs_autoselect('port_SDA', 'Порт SDA*:', [], old('SDA'),
                                        false, false, [], null) }}
                                </div>
                            </div>

                            {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}

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
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/termostat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        let modal_btn_index = -1;
        let methods = [];

        $(document).ready(function () {
            initTermostatForm();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SCL").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SDA").chosen({width:"100%", no_results_text: "Не найдено"});


            $('#usensor_form').submit(function(e) {
                if ($("#usensor_form input[name=type]").length && !$("#usensor_form input[name=type]:checked").val()) {
                    $('#info_modal_body').html('<span class="text-danger">Не указан тип датчика</span>');
                    $('#init_btn').click();
                    return false;
                }
            });

            $("#auto_sel_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_on_params');
                hideParamsFields('method_off_params');
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_on', data.methods, -1);
                        $('#auto_sel_method_on').trigger("chosen:updated");
                        createMethodSelect('#auto_sel_method_off', data.methods, -1);
                        $('#auto_sel_method_off').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port_SCL', data.ports, -1);
                        $('#auto_sel_port_SCL').trigger("chosen:updated");

                        createMethodSelect('#auto_sel_port_SDA', data.ports, -1);
                        $('#auto_sel_port_SDA').trigger("chosen:updated");
                    }
                });
            });

            $('#auto_sel_btn_id_object').click(function() {
                modal_btn_index = 1;
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#auto_sel_btn_object').click(function() {
                modal_btn_index = 2;
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#create_object_modal_btn').click(function() {
                let message = validateCreateObject();
                if (message !== '') {
                    showCreateObjectError(message);
                    return false;
                }

                storeObject();
            });

            function storeObject() {
                const name = $("#create_object_modal input[name=object_name]").val().trim();
                const type = $("#create_object_modal input[name=object_type]:checked").val().trim();

                $.ajax({
                    url: storeObjectUrl,
                    data: {'_token': _token, 'name': name, 'type': type},
                    success: function (data) {
                        if (data.result) {
                            hideCreateObjectError();
                            updateObjectSelects(data.objects, data.id);
                            $('#create_object_cancel_btn').click();
                        } else {
                            showCreateObjectError(data.message);
                        }
                    },
                    error: function () {
                        showCreateObjectError('Сервер временно недоступен');
                    }
                });
            }

            function updateObjectSelects(objects, selected) {
                let id = false;

                if (modal_btn_index === 1) {
                    id = $('#auto_sel_id_object').val();
                } else if (modal_btn_index === 2) {
                    id = $('#auto_sel_object').val();
                }

                if (id) {
                    selected = id;
                }

                createObjectSelect('#auto_sel_id_object', objects, modal_btn_index === 1 ? selected : $('#auto_sel_id_object').val());
                createObjectSelect('#auto_sel_object', objects, modal_btn_index === 2 ? selected : $('#auto_sel_object').val());
            }

            $('#termostat_form [name=object_type]').change(function(){
                if ($(this).val() === 'manual') {
                    $('#auto_object_div').hide();
                    $('#manual_object_div').show();
                } else {
                    $('#manual_object_div').hide();
                    $('#auto_object_div').show();
                }
                return true;
            });
        });
    </script>
@endsection
