@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование котла № '. $boiler->id_object . ' «' . $boiler->name .'»',
        'links' => [ route('engineering.index') => 'Котлы'],
        'last_link' => 'Редактирование котла',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('engineering.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок устройств</a>
                        <a href="{{ route('boiler.edit', $boiler->id_object) }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($boiler, ['route' => ['boiler.update', $boiler->id_object], 'id' => 'boiler_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            <ul class="nav nav-tabs customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active"  data-toggle="tab" href="#boilertab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                                <li class="nav-item"> <a class="nav-link"  data-toggle="tab" href="#boilertab2"  role="tab"><span class="hidden-sm-up"><i class="ti-command"></i></span> <span class="hidden-xs-down">Параметры</span></a> </li>
                                <!-- <li class="nav-item"> <a class="nav-link"  data-toggle="tab" href="#boilertab2"  role="tab"><span class="hidden-sm-up"><i class="ti-command"></i></span> <span class="hidden-xs-down">Режим управления</span></a> </li> -->
                                <li class="nav-item"> <a class="nav-link"  data-toggle="tab" href="#boilertab3"  role="tab"><span class="hidden-sm-up"><i class="ti-command"></i></span> <span class="hidden-xs-down">Методы</span></a> </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane p-20 active" id="boilertab1" role="tabpanel">
                                    @include('engineering/boiler/edit_tabs/main')
                                </div>
                                <div class="tab-pane p-20" id="boilertab2" role="tabpanel">
                                    @include('engineering/boiler/edit_tabs/options')
                                </div>
                                <!-- <div class="tab-pane p-20" id="boilertab2" role="tabpanel"> -->
                                    {{-- @include('engineering/boiler/edit_tabs/control_mode') --}}
                                <!-- </div> -->
                                <div class="tab-pane p-20" id="boilertab3" role="tabpanel">
                                    @include('objects.methods', ['object' => $boiler->object])
                                </div>
                            </div>
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
    @include('components.info_modal')
    @include('components.del_modal')
    @include('objects.method_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const url_delete_boiler_auto = "{{ route('ajax.boiler.auto.delete') }}";
        const url_methods = "{{ route('ajax.objects.methods') }}";
        const sub_data_url = "{{ route('ajax.load.data') }}";
        const object_id = "{{ $boiler->id_object }}";
        const store_url = "{{ route('ajax.methods.store') }}";
        const del_url = "{{ route('ajax.methods.delete') }}";
        const url_device = "{{ route('ajax.devices.type_controller') }}";
        const is_super_admin = "{{ auth()->user()->is_super_admin }}";
        let is_added = 0;
        let del_message;

        function deleteBoilerAuto(boilerAutoId) {
            $.ajax({
                url: url_delete_boiler_auto,
                data: {
                    '_token': _token, 'boiler_auto_id': boilerAutoId,
                },
                success: function (data) {
                    if (data.result) {
                        $("#deleteBoilerAuto"+boilerAutoId).parent().parent().remove();
                    }
                }
            });
        }

        function initMethodsVar(object_id) {
            if (object_id) {
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                    }
                });
            }
        }

        if ('{{ $boiler->mode }}' == 'manual') {
            $('#manual_set_value').removeAttr('hidden');
            $('#boiler_form input[name=set_value]').removeAttr('disabled');
            $('#boiler_form input[name=set_value]').attr('required', true);
            $('#AutoFieldsContainer').attr('hidden', true);
            $('#addAutoFieldsBtn').attr('hidden', true);
            $('#AutoFieldsContainer input').removeAttr('required');
        } else {
            $('#addAutoFieldsBtn').removeAttr('hidden');
            $('#AutoFieldsContainer').removeAttr('hidden');
            $('#boiler_form input[name=set_value]').removeAttr('required');
            $('#manual_set_value').attr('hidden', true);
            if ('{{ $boiler->object->boilerAuto->isEmpty() }}' && !is_added) {
                $("#AutoFieldsContainer").append($('<div class="moduleFields form-group row">' +
                            '<label class="control-label text-right col-md-3 label-fix"><strong>Уличная температура:</strong></label>' +
                            '<div class="col-md-2"><input class="form-control" name="t_out[]" autocomplete="off" required></div>' +
                            '<label class="control-label text-right col-md-3 label-fix"><strong>Температура теплоносителя:</strong></label>' +
                            '<div class="col-md-2"><input class="form-control" name="t_water[]" autocomplete="off" required></div>' +
                        '</div>'));
                is_added = 1;
            }
        }

        $(document).ready(function () {
            initMethodsVar("{{ $boiler->id_object }}");
            $("#auto_sel_outdoor_sensor").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_gateway_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#boiler_form input[name=mode]').change(function() {
                var options = $('#boiler_form input[name=mode]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'manual') {
                    $('#manual_set_value').removeAttr('hidden');
                    $('#boiler_form input[name=set_value]').removeAttr('disabled');
                    $('#boiler_form input[name=set_value]').attr('required', true);
                    $('#AutoFieldsContainer').attr('hidden', true);
                    $('#addAutoFieldsBtn').attr('hidden', true);
                    $('#AutoFieldsContainer input').removeAttr('required');
                } else {
                    $('#addAutoFieldsBtn').removeAttr('hidden');
                    $('#AutoFieldsContainer').removeAttr('hidden');
                    $('#boiler_form input[name=set_value]').removeAttr('required');
                    $('#manual_set_value').attr('hidden', true);
                    if ('{{ $boiler->object->boilerAuto->isEmpty() }}' && !is_added) {
                        $("#AutoFieldsContainer").append($('<div class="moduleFields form-group row">' +
                                    '<label class="control-label text-right col-md-3 label-fix"><strong>Уличная температура:</strong></label>' +
                                    '<div class="col-md-2"><input class="form-control" name="t_out[]" autocomplete="off" required></div>' +
                                    '<label class="control-label text-right col-md-3 label-fix"><strong>Температура теплоносителя:</strong></label>' +
                                    '<div class="col-md-2"><input class="form-control" name="t_water[]" autocomplete="off" required></div>' +
                                '</div>'));
                        is_added = 1;
                    }
                }
            });

            function createNewFields() {
                var newFields = $('<div class="moduleFields form-group row">' +
                                    '<label class="control-label text-right col-md-3 label-fix"><strong>Уличная температура:</strong></label>' +
                                    '<div class="col-md-2"><input class="form-control" name="t_out[]" autocomplete="off" required></div>' +
                                    '<label class="control-label text-right col-md-3 label-fix"><strong>Температура теплоносителя:</strong></label>' +
                                    '<div class="col-md-2"><input class="form-control" name="t_water[]" autocomplete="off" required></div>' +
                                    '<div class="col-sm-2"><button type="button" class="deleteModuleBtn btn btn-outline-danger">Удалить</button></div>' +
                                '</div>');
                return newFields;
            }

            $("#addAutoFieldsBtn").click(function() {
                var newFields = createNewFields();
                $("#AutoFieldsContainer").append(newFields);
                $(".col-sm-2 .deleteModuleBtn").click(function() {
                    $(this).parent().parent().remove();
                });
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
