@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование датчика № '. $drycontact->object['id']  . ' «' . $drycontact->name .'»',
        'links' => [ route('drycontacts.index') => 'Сухие контакты'],
        'last_link' => 'Редактирование датчика'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('drycontacts.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок сухих контактов</a>
                        <a href="{{ route('drycontacts.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить сухой контакт</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($drycontact, ['route' => ['drycontacts.update', $drycontact->id], 'id' => 'drycontact_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID:', $drycontact->object['id'] ) }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        @if(($drycontact->object && $drycontact->object->is_system) || !$can['devices.show-object'])
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="">
                                    Объект:     </label>
                                <div class="col-md-9">
                                    <div class="mt-2">
                                        <a class="a-color" href="{{ route('objects.edit', [$drycontact->id_object]) }}">
                                            {{ $drycontact->object->name }}
                                            @if($drycontact->object && $drycontact->object->is_system) (системный) @endif</a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_object" value="{{ $drycontact->id_object }}">
                        @else
                            {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $drycontact->id_object), false, false, ['required' => true]) }}
                        @endif


                        <div class="col-sm-12 pr-0 mt-4">
                            {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
                               false, false, [], null) }}

                            {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
                                false, false, [], null) }}
                        </div>


                        {{ Form::bs_title('Действие при замыкании') }}

                        {{ Form::bs_autoselect('object_on', 'Объект:', $objects, old('object_on', $object_on),
                            false, false, [], null, 'Объект, на который воздействуем') }}

                        {{ Form::bs_autoselect('method_on', 'Метод:', $methods_on, old('method_on', $method_on),
                            false, false, [], null, 'Метод объекта при замыкании контакта') }}

                        <div class="form-group row" id="param_method_on_div"
                             @if(is_null($drycontact->param_method_on) && !old('method_on')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="param_method_on"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="param_method_on_label" for="param_method_on">
                                        {{ optional($drycontact->emethod_on)->params }}*:</label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="param_method_on" name="param_method_on"
                                               type="text" value="{{ old('param_method_on', $drycontact->param_method_on) }}">
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{ Form::bs_title('Действие при размыкании') }}

                        {{ Form::bs_autoselect('object_off', 'Объект:', $objects, old('object_off', $object_off),
                            false, false, [], null, 'Объект, на который воздействуем') }}

                        {{ Form::bs_autoselect('method_off', 'Метод:', $methods_off, old('method_off', $method_off),
                            false, false, [], null, 'Метод объекта при размыкании контакта') }}

                        <div class="form-group row" id="param_method_off_div"
                             @if(is_null($drycontact->param_method_off) && !old('method_off')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="param_method_off"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="param_method_off_label" for="param_method_off">
                                        {{ optional($drycontact->emethod_off)->params }}*:</label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="param_method_off" name="param_method_off"
                                               type="text" value="{{ old('param_method_off', $drycontact->param_method_off) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('messages.two')
                    </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $drycontact->object])
                    @include('objects.sheduler', ['object' => $drycontact->object])

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
    <script src="{{ asset('ela/js/pagescripts/drycontact.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($drycontact->object)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;
        let del_message;

        $(document).ready(function () {



            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object_on").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_on").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object_off").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_off").chosen({width:"100%", no_results_text: "Не найдено"});


            initDrycontactForm();

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

            $("#auto_sel_object_on").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('param_method_on');

                getMethods(object_id, '#auto_sel_method_on');
            });

            $("#auto_sel_object_off").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('param_method_off');

                getMethods(object_id, '#auto_sel_method_off');

            });


            //при загрузке страницы подгружаем методы для выбранного объекта
            getMethods($("#auto_sel_object_on").val(), '#auto_sel_method_on',  '{{ $method_on }}');
            getMethods($("#auto_sel_object_off").val(), '#auto_sel_method_off', '{{ $method_off }}');



            $("#auto_sel_method_on").chosen().change(function() {
                loadMethods($(this).val(), 'param_method_on', '#drycontact_form');
            });



            $("#auto_sel_method_off").chosen().change(function() {
                loadMethods($(this).val(), 'param_method_off', '#drycontact_form');
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
