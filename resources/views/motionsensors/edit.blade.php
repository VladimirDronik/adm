@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование датчика № '. $motionsensor->id . ' «' . $motionsensor->name .'»',
        'links' => [ route('motionsensors.index') => 'Датчики движения'],
        'last_link' => 'Редактирование датчика'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('motionsensors.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок датчиков движения</a>
                        <a href="{{ route('motionsensors.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик движения</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($motionsensor, ['route' => ['motionsensors.update', $motionsensor->id], 'id' => 'motionsensor_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID:', $motionsensor->id) }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        @if(($motionsensor->object && $motionsensor->object->is_system) || !$can['devices.show-object'])
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="">
                                    Объект:     </label>
                                <div class="col-md-9">
                                    <div class="mt-2">
                                        <a class="a-color" href="{{ route('objects.edit', [$motionsensor->id_object]) }}">
                                            {{ $motionsensor->object->name }}
                                            @if($motionsensor->object && $motionsensor->object->is_system) (системный) @endif</a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_object" value="{{ $motionsensor->id_object }}">
                        @else
                            {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $motionsensor->id_object), false, false, ['required' => true]) }}
                        @endif

                        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId),
                                                  false, false, [], null) }}

                        {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('portId', is_null($portId) ? 0 : $portId),
                            false, false, [], null) }}


                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие при нормальном режиме</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('object_normal', 'Объект:', $objects,  old('object_normal', is_null($object_normal) ? 0 : $object_normal),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}

                                {{ Form::bs_autoselect('method_normal', 'Метод:', $methods_normal, old('method_normal', is_null($motionsensor->method_normal) ? 0 : $motionsensor->method_normal),
                            false, false, [], null, 'Метод, который вызывается при срабатывании датчика в нормальном режиме') }}
                            </div>
                        </div>




                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие при эко режиме</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('object_eco', 'Объект:', $objects,  old('object_eco', is_null($object_eco) ? 0 : $object_eco),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}

                                {{ Form::bs_autoselect('method_eco', 'Метод:', $methods_eco, old('method_eco', is_null($motionsensor->method_eco) ? 0 : $motionsensor->method_eco),
                            false, false, [], null, 'Метод, который вызывается при срабатывании датчика в эко режиме') }}

                            </div>
                        </div>


                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие при ночном режиме</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('object_night', 'Объект:', $objects, old('object_night', is_null($object_night) ? 0 : $object_night),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}

                                {{ Form::bs_autoselect('method_night', 'Метод:',  $methods_night, old('method_night', is_null($motionsensor->method_night) ? 0 : $motionsensor->method_night),
                            false, false, [], null, 'Метод, который вызывается при срабатывании датчика в ночном режиме') }}

                            </div>
                        </div>



                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие при утреннем режиме</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('object_morning', 'Объект:', $objects, old('object_morning', is_null($object_morning) ? 0 : $object_morning),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}


                                {{ Form::bs_autoselect('method_morning', 'Метод:', $methods_morning, old('method_morning', is_null($motionsensor->method_morning) ? 0 : $motionsensor->method_morning),
                                    false, false, [], null, 'Метод, который вызывается при срабатывании датчика в утреннем режиме (сумерки)') }}

                            </div>
                        </div>




                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие при вечернем режиме</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('object_evening', 'Объект:', $objects, old('object_evening', is_null($object_evening) ? 0 : $object_evening),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}


                                {{ Form::bs_autoselect('method_evening', 'Метод:', $methods_evening, old('method_evening', is_null($motionsensor->method_evening) ? 0 : $motionsensor->method_evening),
                           false, false, [], null, 'Метод, который вызывается при срабатывании датчика в вечернем режиме (сумерки)') }}

                            </div>
                        </div>


                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие в режиме охраны</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('object_guard', 'Объект:', $objects, old('object_guard', is_null($object_guard) ? 0 : $object_guard),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}


                                {{ Form::bs_autoselect('method_guard', 'Метод:', $methods_guard, old('method_guard', is_null($motionsensor->method_guard) ? 0 : $motionsensor->method_guard),
                           false, false, [], null, 'Метод, который вызывается при срабатывании датчика в режиме охраны') }}

                            </div>
                        </div>




                        <div class="row" id="select_methods_div">

                            <div class="col-sm-11 pr-0">
                                <div style="height: 10px;">&nbsp;</div>
                                <hr>
                                <h4>Действие при пороговом значении светостата</h4>
                                <div style="height: 20px;">&nbsp;</div>
                            </div>

                            <div class="col-sm-12 pr-0 mt-4">

                                {{ Form::bs_autoselect('lightstat', 'Светостат:', $lightstats, old('lightstat', is_null($motionsensor->lightstat) ? 0 : $motionsensor->lightstat),
                          false, false, [],  null, 'Светостат, значение которого будем проверять') }}

                                {{ Form::bs_radio('equality', 'Если значение светостата:', $equality, old('equality', $motionsensor->equality), ['required' => true]) }}

                                {{ Form::bs_text('lightvalue', 'Значение освещенности:', old('lightvalue', $motionsensor->lightvalue)) }}

                                {{ Form::bs_autoselect('object_light', 'Объект:', $objects, old('object_guard', is_null($object_light) ? 0 : $object_light),
                          false, false, [],  null, 'Объект, методы которого интересуют') }}

                                {{ Form::bs_autoselect('method_light', 'Метод:', $methods_light, old('method_guard', is_null($motionsensor->method_light) ? 0 : $motionsensor->method_light),
                           false, false, [], null, 'Метод, который вызывается при пороговом значнии светостата') }}

                            </div>
                        </div>
                        @include('messages.two')
                    </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $motionsensor->object])
                    @include('objects.events', ['object' => $motionsensor->object])

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
                <button type="button" id="init_message_btn" style="display: none;" data-toggle="modal" data-target="#message_modal">
            </div>
        </div>
    </div>

    @include('objects.message_modal')
    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/switch.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';;
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($motionsensor->object)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;
        let methods = [];
        let del_message;

        $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_normal").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_normal").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_eco").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_eco").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_night").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_night").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_morning").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_morning").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_evening").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_evening").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_guard").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_guard").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_object_light").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_method_light").chosen({width:"100%", no_results_text: "Не найдено"});

        $("#auto_sel_lightstatj").chosen({width:"100%", no_results_text: "Не найдено"});

        $(document).ready(function () {
            initSwitchForm();

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

            $("#auto_sel_object_normal").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_normal', data.methods, -1);
                        $('#auto_sel_method_normal').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_object_eco").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_eco', data.methods, -1);
                        $('#auto_sel_method_eco').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_object_night").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_night', data.methods, -1);
                        $('#auto_sel_method_night').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_object_morning").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_morning', data.methods, -1);
                        $('#auto_sel_method_morning').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_object_evening").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_evening', data.methods, -1);
                        $('#auto_sel_method_evening').trigger("chosen:updated");
                    }
                });
            });


            $("#auto_sel_object_guard").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_guard', data.methods, -1);
                        $('#auto_sel_method_guard').trigger("chosen:updated");
                    }
                });
            });


            $("#auto_sel_object_light").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_light', data.methods, -1);
                        $('#auto_sel_method_light').trigger("chosen:updated");
                    }
                });
            });


            $("#auto_sel_device_id").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': object_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        methods = data.ports;
                        createMethodSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }
                });
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
                const id = $('#auto_sel_id_object').val();
                if (id) {
                    selected = id;
                }
                createObjectSelect('#auto_sel_id_object', objects, selected);
            }

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

            //messages
            $('#apply_message_btn').click(clickApplyMessageBtn);

            // edit messages method
            $('body').on('click', '.edit_message_btn', clickEditMessageBtn);

            //delete message
            $('body').on('click', '.del_message_btn', function() {
                del_message = $(this).attr('data-method');
                $('#del_modal_body').text('Удалить уведомление ?');
                $('#del_init_btn').click();
            });

            // methods

            const cancel_btn = $('#cancel_btn');

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(clickApplyBtn);

            // edit method
            $('body').on('click', '.edit_btn', clickEditBtn);

            // change easy/script/none in modal
            $('input[type=radio][name=actions]').change(changeRadioActions);

            // delete method
            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить метод «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(clickDelBtn);
        });
    </script>
@endsection
