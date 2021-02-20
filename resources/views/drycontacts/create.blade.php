@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление сухого контакта', 'links' => [ route('switches.index') => 'Сухие контакты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('drycontacts.index') }}" class="btn btn-success m-b-10 m-l-5">Список сухих контактов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'drycontacts.store', 'method' => 'post', 'id' => 'switch_form', 'class' => 'form-horizontal form-bordered']) !!}
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
                                            При создании сухого контакта будет создан объект с таким же названием.
                                        </p>
                                    </div>
                                    <div class="col-sm-12 pr-0 mt-4">
                                        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'),
                                           false, false, [], null) }}

                                        {{ Form::bs_autoselect('port_id', 'Порт:', [], old('port_id'),
                                            false, false, [], null) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::bs_title('Действие при замыкании') }}

                    {{ Form::bs_autoselect('object_on', 'Объект:', $objects, old('object_on'),
                        false, false, [], null, 'Объект, на который воздействуем') }}

                    {{ Form::bs_autoselect('method_on', 'Метод:', [], old('method_on'),
                        false, false, [], null, 'Метод объекта при замыкании контакта') }}

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

                    {{ Form::bs_title('Действие при размыкании') }}

                    {{ Form::bs_autoselect('object_off', 'Объект:', $objects, old('object_off'),
                        false, false, [], null, 'Объект, на который воздействуем') }}

                    {{ Form::bs_autoselect('method_off', 'Метод:', [], old('method_off'),
                        false, false, [], null, 'Метод объекта при размыкании контакта') }}

                    <div class="form-group row" id="method_off_params_div"
                         @if(!old('method_off')) style="display: none;" @endif>
                        <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_off_params"></label>
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
    <script src="{{ asset('ela/js/pagescripts/switch.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';

        $(document).ready(function () {
            initSwitchForm();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_on").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_on").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object_off").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_off").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_on").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_on_params');

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {

                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_on', data.methods, -1);
                        $('#auto_sel_method_on').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_object_off").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_off_params');

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {

                        methods = data.methods;
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
                        createMethodSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
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

            function createMethodSelect(target, options, selected) {
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

            $('#switch_form [name=object_type]').change(function(){
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
