@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование выключателя № '. $switch->object['id'] . ' «' . $switch->name .'»',
        'links' => [ route('switches.index') => 'Выключатели'],
        'last_link' => 'Редактирование выключателя'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('switches.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок выключателей</a>
                        <a href="{{ route('switches.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить выключатель</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($switch, ['route' => ['switches.update', $switch->id], 'id' => 'switch_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID объекта:', $switch->object['id']) }}
                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" for="">
                                Тип выключателя:     </label>
                            <div class="col-md-9">
                                <div class="mt-2">
                                    {{ $switch->rus_type }}
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        @if(($switch->object && $switch->object->is_system) || !$can['devices.show-object'])
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="">
                                    Объект:     </label>
                                <div class="col-md-9">
                                    <div class="mt-2">
                                        <a class="a-color" href="{{ route('objects.edit', [$switch->id_object]) }}">
                                            {{ $switch->object->name }}
                                            @if($switch->object && $switch->object->is_system) (системный) @endif</a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_object" value="{{ $switch->id_object }}">
                        @else
                            {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $switch->id_object), false, false, ['required' => true]) }}
                        @endif

                        <div class="col-sm-12 pr-0 mt-4">
                            {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
                               false, false, [], null) }}


                            <div id='port_id_div' @if ($ports==null) style="display: none" @endif>
                                {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
                                  false, false, [], null) }}
                            </div>

                            <div id='hitepro_devices_div' @if ($hp_devices==null) style="display: none" @endif>
                                {{ Form::bs_autoselect('hitepro_devices', 'Устройство:', $hp_devices, old('hiteProDevices', $hp_device),
                                    false, false, [], null) }}
                            </div>
                        </div>


                        {{ Form::bs_title('Одиночное нажатие') }}

                        {{ Form::bs_autoselect('object', 'Объект:', $objects, old('object', $object),
                            false, false, [], null, 'Объект, на который воздействуем') }}

                        {{ Form::bs_autoselect('method', 'Метод:', $methods, old('method', $method),
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



                        {{ Form::bs_title('Двойное нажатие') }}


                        {{ Form::bs_autoselect('object_dc', 'Объект:', $objects, old('object_dc', $object_dc),
                            false, false, [], null, 'Объект, на который воздействуем') }}

                        {{ Form::bs_autoselect('method_dc', 'Метод:', $methods_dc, old('method_dc', $method_dc),
                            false, false, [], null, 'Метод объекта при двойном нажатии кнопки') }}

                        <div class="form-group row" id="method_dc_params_div"
                             @if(!old('method')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_dc_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="method_dc_params_label" for="method_dc_params">...</label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="method_dc_params" name="method_dc_params"
                                               type="text" value="{{ old('method_params') }}">
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{ Form::bs_title('Длительное нажатие') }}

                        {{ Form::bs_autoselect('object_lc', 'Объект:', $objects, old('object_lc', $object_lc),
                            false, false, [], null, 'Объект, на который воздействуем') }}

                        {{ Form::bs_autoselect('method_lc', 'Метод:', $methods_lc, old('method_lc', $method_lc),
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

                    @include('messages.two')

                </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $switch->object])
                    @include('objects.events', ['object' => $switch->object])

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
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($switch->object)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;
        let del_message;

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

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {

                        methods = data.methods;
                        createMethodSelect('#auto_sel_method', data.methods, -1);
                        $('#auto_sel_method').trigger("chosen:updated");

                    }
                });
            });

            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN', 'type': 'switch, socket'},
                    success: function (data) {
                        methods = data.ports;
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

            $("#auto_sel_object_lc").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_lc_params');

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {

                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_lc', data.methods, -1);
                        $('#auto_sel_method_lc').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_object_dc").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_params_dc');

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {

                        methods = data.methods;
                        createMethodSelect('#auto_sel_method_dc', data.methods, -1);
                        $('#auto_sel_method_dc').trigger("chosen:updated");
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


        function createPortSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="null">Не выбрано</option>';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id)
                    s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                else
                    s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
            }
            sel.append(s);
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
    </script>
@endsection
