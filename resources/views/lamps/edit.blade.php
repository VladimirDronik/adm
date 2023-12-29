@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование лампы № '. $lamp->object['id'] . ' «' . $lamp->name .'»',
        'links' => [ route('lamps.index') => 'Лампы'],
        'last_link' => 'Редактирование лампы'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('lamps.index') }}" class="btn btn-success m-b-10 m-l-5">Список ламп</a>
                        <a href="{{ route('lamps.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить лампу</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($lamp, ['route' => ['lamps.update', $lamp->id], 'id' => 'lamp_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
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
                                @include('lamps/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20 @if($tab==2) active @endif" id="portstab2" role="tabpanel">
                                @include('lamps/edit_tabs/prop')
                            </div>
                            <div class="tab-pane p-20 @if($tab==4) active @endif" id="portstab4" role="tabpanel">
                                @include('lamps/edit_tabs/events')
                                @include('objects.events', ['object' => $lamp->object])
                            </div>
                            <div class="tab-pane p-20 @if($tab==3) active @endif" id="portstab3" role="tabpanel" style="width: 1000px;">
                                @include('lamps.edit_tabs.methods', ['systemMethods' => $systemMethods])
                            </div>
                            <div class="tab-pane p-20 @if($tab==5) active @endif" id="portstab5" role="tabpanel">
                                @include('objects.sheduler', ['object' => $lamp->object])
                            </div>
                        </div>
                        <input type="hidden" id="tabs-sel" value="{{ $tab }}">
                        <input type="hidden" id="event_idobject" name="event_idobject" value="{{ $lamp->iobject['id'] }}">
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
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/lamp.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/events.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/service.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';
        const object_id = '{{ optional($lamp->object)->id }}';
        const url_mod_bus_slavers_registers = '{{ route('ajax.mod_bus.slavers.registers') }}';
        let methodsIdWithRegisters = {!! json_encode($methodsIdWithRegisters) !!};
        let del_id;

        $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_register_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_gateway_id").chosen({width:"100%", no_results_text: "Не найдено"});

        function createRegisterSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';

            $.each(options, function(key, value) {
                if (selected == key)
                    s += '<option selected value="' + key + '">' + value + '</option>';
                else
                    s += '<option value="' + key + '">' + value + '</option>';
            });
            sel.append(s);
        }

        function createPortSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id)
                    s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                else
                    s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
            }
            sel.append(s);
        }

        if ($('#lamp_form input[name=is_dimer]').is(':checked')) {
            $('#dimer_fields_div').removeAttr("hidden");
            $('#lamp_form input[name=value]').removeAttr("disabled");
            $('#lamp_form input[name=speed]').removeAttr("disabled");
        } else {
            $('#dimer_fields_div').attr("hidden", true);
            $('#lamp_form input[name=value]').attr("disabled", true);
            $('#lamp_form input[name=speed]').attr("disabled", true);
        }

        if ('{{ $lamp->gateway_type ==  \App\Models\HomeObject::GATEWAY_MODBUS }}') {
            let slaver_id = $("#auto_sel_gateway_id").chosen().val();
            $.ajax({
                url: url_mod_bus_slavers_registers,
                data: {'slaver_id': slaver_id},
                success: function (data) {
                    createRegisterSelect('#auto_sel_register_id', data, 0);
                    $('#auto_sel_register_id').trigger("chosen:updated");
                }
            });
        } else {
            let device_id = $("#auto_sel_gateway_id").chosen().val();
            $.ajax({
                url: url_ports,
                data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'switch, socket'},
                success: function (data) {
                    createPortSelect('#auto_sel_port_id', data.ports, '{{ $currentPort ? $currentPort->id : 0 }}');
                    $('#auto_sel_port_id').trigger("chosen:updated");
                }
            });
        }

        $(document).ready(function () {
            $('#del_modal_btn').click(clickDelBtn);

            initLampForm();
            initActionModal();
            serviceInit();

            if ('{{ $lamp->gateway_type ==  \App\Models\HomeObject::GATEWAY_MODBUS }}') {
                $("#auto_sel_gateway_id").chosen().change(function() {
                    $.ajax({
                        url: url_mod_bus_slavers_registers,
                        data: {'slaver_id': $(this).val()},
                        success: function (data) {
                            createRegisterSelect('#auto_sel_register_id', data, 0);
                            $('#auto_sel_register_id').trigger("chosen:updated");
                        }
                    });
                });

                var promises = Object.entries(methodsIdWithRegisters).map(function ([methodId, registerId]) {
                    $("#auto_sel_slaver_id_" + methodId).chosen({width:"100%", no_results_text: "Не найдено"});
                    return $.ajax({
                        url: url_mod_bus_slavers_registers,
                        data: {'slaver_id': $("#auto_sel_slaver_id_" + methodId).chosen().val()},
                        success: function (data) {
                            createRegisterSelect('#auto_sel_register_id_' + methodId, data, registerId);
                            $('#auto_sel_register_id_' + methodId).trigger("chosen:updated");
                        }
                    });
                });

                $.when.apply($, promises).then(function () {
                    $.each(methodsIdWithRegisters, function(methodId, registerId) {
                        $("#auto_sel_register_id_" + methodId).chosen({width:"100%", no_results_text: "Не найдено"});

                        $("#auto_sel_slaver_id_" + methodId).chosen().change(function() {
                            $.ajax({
                                url: url_mod_bus_slavers_registers,
                                data: {'slaver_id': $(this).val()},
                                success: function (data) {
                                    createRegisterSelect('#auto_sel_register_id_' + methodId, data, registerId);
                                    $('#auto_sel_register_id_' + methodId).trigger("chosen:updated");
                                }
                            });
                        });
                    });
                });
            } else {
                $("#auto_sel_gateway_id").chosen().change(function() {
                    let device_id = $(this).val();
                    $.ajax({
                        url: url_ports,
                        data: {'_token': _token, 'device_id': device_id, 'status': 'out', 'type': 'switch, socket'},
                        success: function (data) {
                            createPortSelect('#auto_sel_port_id', data.ports, 0);
                            $('#auto_sel_port_id').trigger("chosen:updated");
                        }
                    });
                });
            }

            $('#lamp_form input[name=is_dimer]').change(function() {
                if ($('#lamp_form input[name=is_dimer]').is(':checked')) {
                    $('#dimer_fields_div').removeAttr("hidden");
                    $('#lamp_form input[name=value]').removeAttr("disabled");
                    $('#lamp_form input[name=speed]').removeAttr("disabled");
                } else {
                    $('#dimer_fields_div').attr("hidden", true);
                    $('#lamp_form input[name=value]').attr("disabled", true);
                    $('#lamp_form input[name=speed]').attr("disabled", true);
                }
            });

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


        });
    </script>
@endsection
