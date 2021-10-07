@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование гигростата № '. $hygrostat->iobject['id'],
        'links' => [ route('hygrostats.index') => 'Гигростаты'],
        'last_link' => 'Редактирование термостата'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('hygrostats.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок гигростатов</a>
                        <a href="{{ route('hygrostats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить гигростат</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">




                    {!! Form::model($hygrostat, ['route' => ['hygrostats.update', $hygrostat->id],
                            'id' => 'hygrostat_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">

                        {{ Form::bs_alert() }}


                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item"> <a class="nav-link @if($tab==1) active @endif"  data-toggle="tab" href="#portstab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==2) active @endif"  data-toggle="tab" href="#portstab2"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Свойства</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==4) active @endif"  data-toggle="tab" href="#portstab4"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">События</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==3) active @endif"  data-toggle="tab" href="#portstab3"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Методы</span></a> </li>
                            <li class="nav-item"> <a class="nav-link @if($tab==5) active @endif"  data-toggle="tab" href="#portstab5"  role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Планировщик</span></a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 @if($tab==1) active @endif" id="portstab1" role="tabpanel">
                                @include('hygrostats/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                                @include('hygrostats/edit_tabs/prop')
                            </div>
                            <div class="tab-pane p-20 @if($tab==4) active @endif" id="portstab4" role="tabpanel">
                                @include('hygrostats/edit_tabs/events')
                                @include('objects.events', ['object' => $hygrostat->iobject])
                            </div>
                            <div class="tab-pane p-20 @if($tab==3) active @endif" id="portstab3" role="tabpanel">
                                @include('objects.methods', ['object' => $hygrostat->iobject])
                            </div>
                            <div class="tab-pane p-20 @if($tab==5) active @endif" id="portstab5" role="tabpanel">
                                @include('objects.sheduler', ['object' => $hygrostat->iobject])
                            </div>
                        </div>
                        <input type="hidden" id="tabs-sel" value="{{ $tab }}">
                        <input type="hidden" id="event_idobject" name="event_idobject" value="{{ $hygrostat->iobject['id'] }}">


                    </div>
                    {{ Form::bs_submit_btn() }}

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
    <script src="{{ asset('ela/js/pagescripts/hygrostat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/events.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($hygrostat->iobject)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_device = '{{ route('ajax.devices.type_controller') }}';


        let del_id;
        let del_type;
        let modal_btn_index = -1;
        let methods = [];
        let del_message;
        let tempActions = [];

        $(document).ready(function () {
            initHygrostatForm();
            initActionModal();
            initMethodsVar({{ optional($hygrostat->eobject)->id }});

            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_usensor_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_HPController_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_subdev_id").chosen({width:"100%", no_results_text: "Не найдено"});

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
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,1WIRE,1W-BUS,I2C'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_port_id").click(function() {
                alert();
                /*
                let device_id = $("#auto_sel_device_id").val();
                alert(device_id);
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'in'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_port_id', data.ports, -1);
                        $('#auto_sel_port_id').trigger("chosen:updated");
                    }

                });
                 */
            });

            $("#auto_sel_HPController_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'temperature'},
                    success: function (data) {
                        createMethodSelect('#auto_sel_subdev_id', data.hiteProDevices, -1);
                        $('#auto_sel_subdev_id').trigger("chosen:updated");
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

            // methods

            const cancel_btn = $('#cancel_btn');

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(clickApplyBtn);

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


        $('#hygrostat_form [name=placetype_radio]').change(function(){
            if ($(this).val() === 'usensor') {
                $('#usensor_div').show();
                $('#device_div').hide();
                $('#placetype').val('usensor');
            } else {
                $('#usensor_div').hide();
                $('#device_div').show();
                $('#placetype').val('Hite-pro');
            }


            return true;
        });


    </script>
@endsection
