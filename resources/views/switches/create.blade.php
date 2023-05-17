@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление выключателя', 'links' => [ route('switches.index') => 'Выключатели']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('switches.index') }}" class="btn btn-success m-b-10 m-l-5">Список выключателей</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'switches.store', 'method' => 'post', 'id' => 'switch_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_radio('type', 'Тип выключателя*:', $types, old('type', -1), ['required' => true]) }}
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
                                            При создании выключателя будет создан объект с таким же названием.
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

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="one_clk_div" style="display: none">
                            {{ Form::bs_title('Одиночное нажатие') }}

                            {{ Form::bs_autoselect('object', 'Объект:', $objects, old('object'),
                                false, false, [], null, 'Объект, на который воздействуем') }}

                            {{ Form::bs_autoselect('method', 'Метод:', [], old('method'),
                                false, false, [], null, 'Метод объекта при одиночном нажатии кнопки') }}

                            <div class="form-group row" id="method_params_div"
                                @if(!old('method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_params_label" for="method_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_params" name="method_params"
                                                type="text" value="{{ old('method_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div id="double_clk_div" style="display: none">
                            {{ Form::bs_title('Двойное нажатие') }}


                            {{ Form::bs_autoselect('object_dc', 'Объект:', $objects, old('object_dc'),
                                false, false, [], null, 'Объект, на который воздействуем') }}

                            {{ Form::bs_autoselect('method_dc', 'Метод:', [], old('method_dc'),
                                false, false, [], null, 'Метод объекта при двойном нажатии кнопки') }}

                            <div class="form-group row" id="method_dc_params_div"
                                 @if(!old('method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_dc_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_dc_params_label" for="method_dc_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_dc_params" name="method_dc_params"
                                                   type="text" value="{{ old('method_dc_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="long_clk_div" style="display: none">
                            {{ Form::bs_title('Длительное нажатие') }}

                            {{ Form::bs_autoselect('object_lc', 'Объект:', $objects, old('object_lc'),
                                false, false, [], null, 'Объект, на который воздействуем') }}

                            {{ Form::bs_autoselect('method_lc', 'Метод:', [], old('method_lc'),
                                false, false, [], null, 'Метод объекта при длительном нажатии кнопки') }}

                            <div class="form-group row" id="method_lc_params_div"
                                 @if(!old('method_lc')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_lc_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_lc_params_label" for="method_lc_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_lc_params" name="method_lc_params"
                                                   type="text" value="{{ old('method_lc_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <input type="hidden" name="place" id="place">
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
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const url_devices = '{{ route('ajax.devices.get') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';

        $(document).ready(function () {
            initSwitchForm();

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_hitepro_devices").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object_lc").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_lc").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object_dc").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_dc").chosen({width:"100%", no_results_text: "Не найдено"});


            $("#auto_sel_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_params');

                getMethods(object_id, '#auto_sel_method');
            });


            $("#auto_sel_object_lc").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_lc_params');

                getMethods(object_id, '#auto_sel_method_lc');
            });

            $("#auto_sel_object_dc").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_dc_params');

                getMethods(object_id, '#auto_sel_method_dc');
            });




            $("#auto_sel_method").chosen().change(function() {
                loadMethods($(this).val(), 'method_params', '#switch_form');
            });

            $("#auto_sel_method_lc").chosen().change(function() {
                loadMethods($(this).val(), 'method_lc_params', '#switch_form');
            });

            $("#auto_sel_method_dc").chosen().change(function() {
                loadMethods($(this).val(), 'method_dc_params', '#switch_form');
            });





            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC', 'type': 'transmitter'},
                    success: function (data) {

                        if (data.type_device == 'Hite-pro') {
                            $('#port_id_div').hide();
                            $('#double_clk_div').hide();
                            $('#long_clk_div').hide();
                            $('#hitepro_devices_div').show();
                            createPortSelect('#auto_sel_hitepro_devices', data.hiteProDevices, -1);
                            $('#auto_sel_hitepro_devices').trigger("chosen:updated");
                            $('#place').val('Hite-pro');
                        }
                        else {
                            $('#port_id_div').show();
                            $('#double_clk_div').show();
                            $('#long_clk_div').show();
                            $('#hitepro_devices_div').hide();
                            createPortSelect('#auto_sel_port_id', data.ports, -1);
                            $('#auto_sel_port_id').trigger("chosen:updated");
                            $('#place').val('port');
                        }


                        createPortsSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }
                });
            });

            $('#auto_sel_btn_id_object').click(function() {
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#switch_form input[name=type]').change(function() {
                var options = $('#switch_form input[name=type]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'button') {
                    $('#one_clk_div').show();
                    $('#double_clk_div').show();
                    $('#long_clk_div').show();
                } else {
                    $('#one_clk_div').show();
                    $('#double_clk_div').hide();
                    $('#long_clk_div').hide();
                }
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

            function createDevicesSelect(target, options, selected) {
                let sel = $(target);
                sel.html('');
                let s = '<option value="">Не выбрано</option>';
                for (let i = 0; i < options.length; i++) {
                    if (selected == options[i].id)
                        s += '<option selected value="' + options[i].id + '">' + options[i].description + '</option>';
                    else
                        s += '<option value="' + options[i].id + '">' + options[i].description + '</option>';
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

            //При выборе кнопки подгружаем устройства хитпро
            $('#switch_form [name=type]').change(function(){
                if ($(this).val() === 'button') {
                    types = ['Hite-pro', 'Monoblock 14IN/14OUT'];
                } else {
                    types = ['Monoblock 14IN/14OUT'];
                }

                $.ajax({
                    url: url_devices,
                    data: {'_token': _token, 'types': types},
                    success: function (data) {
                        createDevicesSelect('#auto_sel_device_id', data.devices, -1);
                        $('#auto_sel_device_id').trigger("chosen:updated");
                    }
                });

                return true;
            });


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
