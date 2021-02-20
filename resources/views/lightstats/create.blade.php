@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление светостата', 'links' => [ route('lightstats.index') => 'Светостаты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('lightstats.index') }}" class="btn btn-success m-b-10 m-l-5">Список светостатов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'lightstats.store', 'method' => 'post',
                            'id' => 'lightstat_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}


                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                    <strong>Объект светостата*:</strong>
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
                                                        <input type="radio" name="object_type" autocomplete="off"  value="manual">  Выбор из списка
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
                                                При создании светостата будет создан объект с таким же названием.
                                                У объекта будет создан метод «Проверка светостата».
                                                К методу будет привязан системный скрипт «Проверка светостата»
                                                (если такого скрипта нет, то он будет создан) и
                                                будет создано событие «Проверка светостата» (каждые 5 мин).
                                            </p>
                                        </div>
                                        <div class="col-sm-12 pr-0  mt-4">
                                            <div class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-success btn-sm  active ">
                                                    <input type="radio" name="placetype_radio" autocomplete="off"  value="port"> На отдельном порту
                                                </label>

                                                <label class="btn btn-success btn-sm">
                                                    <input type="radio" name="placetype_radio" autocomplete="off" value="usensor">  В составе унив. датчика
                                                </label>

                                                <input type="hidden" id="placetype" name="placetype" value="port">

                                            </div>
                                        </div>

                                        <div class="col-sm-12 pr-0 mt-4" id="single_port_div">
                                            {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'),
                                               false, false, [], null) }}

                                            {{ Form::bs_autoselect('port_SCL', 'Порт SCL:', [], old('port_SCL'),
                                                false, false, [], null) }}

                                            {{ Form::bs_autoselect('port_SDA', 'Порт SDA:', [], old('port_SDA'),
                                               false, false, [], null) }}
                                        </div>


                                        <div class="col-sm-12 pr-0 mt-4" id="usensor_div"  style="display: none;">
                                            {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id'),
                                               false, false, [], null) }}
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div style="height: 10px;">&nbsp;</div>
                            <hr>
                            <div style="height: 40px;">&nbsp;</div>

                            {{ Form::bs_radio('mode', 'Режим*:', $types, old('mode', -1), ['required' => true]) }}

                            {{ Form::bs_number('optimal', 'Оптимальная освещенность*:', null, ['min' => 0, 'max' => 54612, 'required' => true],
                                'Освещенность, которая должна быть в помещении') }}
                            {{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 50), ['min' => 0, 'max' => 1000, 'required' => true]) }}


                            {{ Form::bs_number('min_threshold', 'Минимальная освещенность*:', old('min_threshold', 0), ['min' => 0, 'max' => 54612, 'required' => true],
                                '') }}
                            {{ Form::bs_number('max_threshold', 'Максимальная освещенность*:', old('max_threshold', 54612), ['min' => 0, 'max' => 54612, 'required' => true],
                                '') }}
                            {{ Form::bs_number('min_alarm', 'Мин. аварийная освещенность*:', old('min_alarm', 0), ['min' => 0, 'max' => 54612, 'required' => true],
                                '') }}
                            {{ Form::bs_number('max_alarm', 'Макс. аварийная освещенность*:', old('max_alarm', 54612), ['min' => 0, 'max' => 54612, 'required' => true],
                                '') }}


                            <div style="height: 10px;">&nbsp;</div>
                            <hr>
                            <div style="height: 40px;">&nbsp;</div>

                            {{ Form::bs_autoselect_and_btn('object', 'Объект влияния:', $objects, old('object'),
                                false, false, [], '', '', null, 'Объект, у которого меняем состояние', 3, $can['devices.show-object']) }}

                            {{ Form::bs_autoselect('method_on', 'Метод при включении:', [], old('method_on'),
                                false, false, [], null, 'Метод объекта влияния при срабатывании светостата на включение') }}

                            <div class="form-group row" id="method_on_params_div"
                                 @if(!old('method_on')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_on_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_on_params_label" for="method_on_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_on_params" name="method_on_params"
                                                   type="text" value="{{ old('method_on_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ Form::bs_autoselect('method_off', 'Метод при выключении:', [], old('method_off'),
                                false, false, [], null, 'Метод объекта влияния при срабатывании светостата на выключение') }}

                            <div class="form-group row" id="method_off_params_div"
                                 @if(!old('method_off')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 label-fix" for="method_off_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_off_params_label" for="method_off_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_off_params" name="method_off_params"
                                                   type="text" value="{{ old('method_off_params') }}">
                                        </div>
                                    </div>
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
    <script src="{{ asset('ela/js/pagescripts/lightstat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        let modal_btn_index = -1;
        let methods = [];

        $(document).ready(function () {
            initLightstatForm();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SCL").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SDA").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_usensor_id").chosen({width:"100%", no_results_text: "Не найдено"});

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

            $('#lightstat_form [name=object_type]').change(function(){
                if ($(this).val() === 'manual') {
                    $('#auto_object_div').hide();
                    $('#manual_object_div').show();
                } else {
                    $('#manual_object_div').hide();
                    $('#auto_object_div').show();
                }
                return true;
            });


            $('#lightstat_form [name=placetype_radio]').change(function(){
                if ($(this).val() === 'port') {
                    $('#usensor_div').hide();
                    $('#single_port_div').show();
                    $('#placetype').val('port');
                } else {
                    $('#usensor_div').show();
                    $('#single_port_div').hide();
                    $('#placetype').val('usensor');
                }

                return true;
            });

        });
    </script>
@endsection
