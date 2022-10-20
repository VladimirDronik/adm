@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление лампы', 'links' => [ route('lamps.index') => 'Лампа']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('lamps.index') }}" class="btn btn-success m-b-10 m-l-5">Список ламп</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'lamps.store', 'method' => 'post', 'id' => 'lamp_form',
                            'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                <strong>Объект*:</strong>
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
                                    <div class="col-sm-11 pr-0">
                                        <p>
                                            При создании лампы будет создан объект с таким же названием.
                                            У объекта будут созданы методы «Включить лампу» и «Выключить лампу».
                                        </p>
                                    </div>
                                    <div class="col-sm-12 pr-0 mt-4">
                                        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'),
                                           false, false, [], null) }}

                                        <div id='port_id_div' style="display: none">
                                            {{ Form::bs_autoselect('port_id', 'Порт:', [], old('port_id'),
                                                false, false, [], null) }}
                                        </div>

                                        <div id='hitepro_devices_div' style="display: none">
                                            {{ Form::bs_autoselect('hitepro_devices', 'Устройство:', [], old('hiteProDevices'),
                                                false, false, [], null) }}
                                        </div>

                                        <input type="hidden" name="place" id="place">
                                    </div>
                                </div>
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
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/lamp.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';

        $(document).ready(function () {
            initLampForm();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_hitepro_devices").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'switch, socket'},
                    success: function (data) {
                        if (data.type_device == 'Hite-pro') {
                            $('#port_id_div').hide();
                            $('#hitepro_devices_div').show();
                            createPortSelect('#auto_sel_hitepro_devices', data.hiteProDevices, -1);
                            $('#auto_sel_hitepro_devices').trigger("chosen:updated");
                            $('#place').val('Hite-pro');
                        }
                        else {
                            $('#port_id_div').show();
                            $('#hitepro_devices_div').hide();
                            createPortSelect('#auto_sel_port_id', data.ports, -1);
                            $('#auto_sel_port_id').trigger("chosen:updated");
                            $('#place').val('port');
                        }
                    }
                });
            });

            $('#auto_sel_btn_id_object').click(function() {
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
                const id = $('#auto_sel_id_object').val();
                if (id) {
                    selected = id;
                }
                createObjectSelect('#auto_sel_id_object', objects, selected);
            }

            $('#lamp_form [name=object_type]').change(function(){
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
