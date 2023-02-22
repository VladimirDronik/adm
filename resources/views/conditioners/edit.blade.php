@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование кондиционера № '. $conditioner->object['id'] . ' «' . $conditioner->conditionerModel->name .'»',
        'links' => [ route('conditioners.index') => 'Кондиционеры'],
        'last_link' => 'Редактирование кондиционера'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('conditioners.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок кондиционеров</a>
                        <a href="{{ route('conditioners.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить кондиционер</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($conditioner, ['route' => ['conditioners.update', $conditioner->id], 'id' => 'conditioner_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item"> <a class="nav-link active"  data-toggle="tab" href="#portstab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                            <li class="nav-item"> <a class="nav-link"  data-toggle="tab" href="#portstab2"  role="tab"><span class="hidden-sm-up"><i class="ti-command"></i></span> <span class="hidden-xs-down">Команды</span></a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 active" id="portstab1" role="tabpanel">
                                @include('conditioners/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20" id="portstab2" role="tabpanel">
                                @include('conditioners/edit_tabs/command')
                            </div>
                        </div>
                        <input type="hidden" id="event_idobject" name="event_idobject" value="{{ isset($conditioner->object['id']) }}">

                    </div>

                </div>
                    {{ Form::bs_submit_btn() }}

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/messages.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/events.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/service.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const store_message_url = '{{ route('ajax.messages.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const del_message_url = '{{ route('ajax.messages.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($conditioner->object)->id }}';
        const is_super_admin = {{ user()->is_super_admin ? 1 : 0 }};
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const url_device = '{{ route('ajax.devices.type_controller') }}';
        let del_id;
        let del_message;

        $(document).ready(function () {


            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_hitepro_devices").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object_lc").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_lc").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_object_dc").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method_dc").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#del_modal_btn').click(clickDelBtn);

            initConditionerForm();
            initActionModal();
            serviceInit();

            // $("#auto_sel_device_id").chosen().change(function() {
            //     let device_id = $(this).val();
            //     $.ajax({
            //         url: url_ports,
            //         data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC', 'type': 'switch, socket'},
            //         success: function (data) {
            //             methods = data.ports;
            //             if (data.type_device == 'Hite-pro') {
            //                 $('#port_id_div').hide();
            //                 $('#hitepro_devices_div').show();
            //                 createPortSelect('#auto_sel_hitepro_devices', data.hiteProDevices, -1);
            //                 $('#auto_sel_hitepro_devices').trigger("chosen:updated");
            //                 $('#place').val('Hite-pro');
            //             }
            //             else {
            //                 $('#port_id_div').show();
            //                 $('#hitepro_devices_div').hide();
            //                 createPortSelect('#auto_sel_port_id', data.ports, -1);
            //                 $('#auto_sel_port_id').trigger("chosen:updated");
            //                 $('#place').val('port');
            //             }
            //         }
            //     });
            // });



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
                loadMethods($(this).val(), 'method_params', '#conditioner_form');
            });

            $("#auto_sel_method_lc").chosen().change(function() {
                loadMethods($(this).val(), 'method_lc_params', '#conditioner_form');
            });

            $("#auto_sel_method_dc").chosen().change(function() {
                loadMethods($(this).val(), 'method_dc_params', '#conditioner_form');
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
