@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование датчика УГ № '. $carbmonoxide->iobject['id'],
        'links' => [ route('carbmonoxide.index') => 'Датчики УГ'],
        'last_link' => 'Редактирование датчика УГ'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('carbmonoxide.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок датчиков УГ</a>
                        <a href="{{ route('carbmonoxide.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик УГ</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($carbmonoxide, ['route' => ['carbmonoxide.update', $carbmonoxide->id],
                            'id' => 'carbmonoxide_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID объекта:', $carbmonoxide->iobject['id']) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}




                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                    <strong>Размещение датчика УГ:</strong>
                                </label>

                                            <div class="col-md-6 pr-0 mt-4" id="single_port_div">
                                                {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId),
                                                   false, false, [], null) }}

                                                {{ Form::bs_autoselect('port', 'Порт:', $ports, old('port', is_null($port) ? 0 : $port),
                                                    false, false, [], null) }}

                                             </div>
                        </div>
                        <div style="height: 10px;">&nbsp;</div>
                        <hr>
                        <div style="height: 40px;">&nbsp;</div>



                        {{ Form::bs_text('calibration', 'Калибровка*:', old('max_threshold', $carbmonoxide->calibration), ['required' => true],
                               '') }} <div style="height: 60px;">&nbsp;</div>

                        {{ Form::bs_number('low_value', 'Нижний аварийный порог*:', old('low_value', $carbmonoxide->low_value), ['min' => 0, 'max' => 1000, 'required' => true]) }}

                        {{ Form::bs_autoselect('low_object', 'Объект влияния:', $objects, old('low_object', $carbmonoxide->low_object),
                          false, false, [],  null, 'Объект, у которого меняем состояние при достищении нижнего порога') }}

                        {{ Form::bs_autoselect('low_method', 'Метод объекта:', $low_methods, old('low_method',  $carbmonoxide->low_method),
                            false, false, [], null, 'Метод объекта влияния при достижении нижнего порога') }}

                        <div style="height: 60px;">&nbsp;</div>

                        {{ Form::bs_number('high_value', 'Верхний аварийный порог*:', old('high_value', $carbmonoxide->high_value), ['min' => 0, 'max' => 5000, 'required' => true],
                            '') }}

                        {{ Form::bs_autoselect('high_object', 'Объект влияния:', $objects, old('high_object', $carbmonoxide->high_object),
                         false, false, [],  null, 'Объект, у которого меняем состояние при достищении верхнего порога') }}


                        {{ Form::bs_autoselect('high_method', 'Метод объекта:', $high_methods, old('high_method', $carbmonoxide->high_method),
                            false, false, [], null, 'Метод объекта влияния при достижении верхнего порога') }}


                        <div style="height: 60px;">&nbsp;</div>


                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $carbmonoxide->room), false, false) }}


                        @include('messages.two')

                    </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $carbmonoxide->iobject])
                    @include('objects.sheduler', ['object' => $carbmonoxide->iobject])

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
    <script src="{{ asset('ela/js/pagescripts/lightstat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';;
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($carbmonoxide->iobject)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;
        let modal_btn_index = -1;
        let methods = [];
        let del_message;

        $(document).ready(function () {

            initLightstatForm();
            initMethodsVar({{ optional($carbmonoxide->eobject)->id }});

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_low_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_high_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_low_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_high_method").chosen({width:"100%", no_results_text: "Не найдено"});



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

            $("#auto_sel_device_id").chosen().change(function() {

                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port', data.ports, -1);
                        $('#auto_sel_port').trigger("chosen:updated");

                    }
                });
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

                createObjectSelect('#auto_sel_id_object', objects,
                    modal_btn_index === 1 ? selected : $('#auto_sel_id_object').val());
                createObjectSelect('#auto_sel_object', objects,
                    modal_btn_index === 2 ? selected : $('#auto_sel_object').val());
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
