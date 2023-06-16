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
                            <li class="nav-item"> <a class="nav-link active"  data-toggle="tab" href="#conditionerstab1"  role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Основное</span></a> </li>
                            <li class="nav-item"> <a class="nav-link"  data-toggle="tab" href="#conditionerstab2"  role="tab"><span class="hidden-sm-up"><i class="ti-command"></i></span> <span class="hidden-xs-down">Команды</span></a> </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane p-20 active" id="conditionerstab1" role="tabpanel">
                                @include('conditioners/edit_tabs/main')
                            </div>
                            <div class="tab-pane p-20" id="conditionerstab2" role="tabpanel">
                                @include('conditioners/edit_tabs/command')
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
    @include('conditioners.code_modals')
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/conditioner.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_code = '{{ route('ajax.conditioners.code') }}';
        const url_read_code = '{{ route('ajax.conditioners.read_code') }}';
        const url_recive_code = '{{ route('ajax.conditioners.recive_code') }}';
        const url_save_code = '{{ route('ajax.conditioners.save_code') }}';
        const url_cancel_reading_code = '{{ route('ajax.conditioners.cancel_reading_code') }}';
        const kind = '{{ $conditionerKind->id }}';
        const ip = '{{ $conditioner->device->ip_address }}';

        $(document).ready(function () {
            initConditionerForm();

            $("#auto_sel_temp").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_operationMode").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_fanMode").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_room").chosen({width:"100%", no_results_text: "Не найдено"});

            $.ajax({
                url: url_code,
                data: {'_token': _token, 'temp': $("#auto_sel_temp").val(), 'operationMode': $("#auto_sel_operationMode").val(), 'fanMode': $("#auto_sel_fanMode").val(), 'kind': kind,},
                success: function (data) {
                    $('#place').val('code');
                    $('#dataCode').val(data.code);
                }
            });

            $("#auto_sel_temp").chosen().change(function() {
                let temp = $(this).val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                $.ajax({
                    url: url_code,
                    data: {'_token': _token, 'temp': temp, 'operationMode': operationMode, 'fanMode': fanMode, 'kind': kind,},
                    success: function (data) {
                        $('#place').val('code');
                        $('#dataCode').val(data.code);
                        $('#auto_sel_temp').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_operationMode").chosen().change(function() {
                let operationMode = $(this).val();
                let temp = $("#auto_sel_temp").val();
                let fanMode = $("#auto_sel_fanMode").val();
                $.ajax({
                    url: url_code,
                    data: {'_token': _token, 'temp': temp, 'operationMode': operationMode, 'fanMode': fanMode, 'kind': kind,},
                    success: function (data) {
                        $('#place').val('code');
                        $('#dataCode').val(data.code);
                        $('#auto_sel_operationMode').trigger("chosen:updated");
                    }
                });
            });

            $("#auto_sel_fanMode").chosen().change(function() {
                let fanMode = $(this).val();
                let operationMode = $("#auto_sel_operationMode").val();
                let temp = $("#auto_sel_temp").val();
                $.ajax({
                    url: url_code,
                    data: {'_token': _token, 'temp': temp, 'operationMode': operationMode, 'fanMode': fanMode, 'kind': kind,},
                    success: function (data) {
                        $('#place').val('code');
                        $('#dataCode').val(data.code);
                        $('#auto_sel_fanMode').trigger("chosen:updated");
                    }
                });
            });

            $('#codeCheckbox').change(function() {
                let active = this.checked ? 1 : 0;
                if (active) {
                    $('#dataCode').prop('disabled', false);
                } else {
                    $('#dataCode').prop('disabled', true);
                }
            });

            $('#readCodeBtn').click(function() {
                let wbMir = $("#wbMir").val();
                let temp = $("#auto_sel_temp").val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                $.ajax({
                    url: url_read_code,
                    data: {'_token': _token, 'wbMir': wbMir, 'ip': ip},
                    success: function (data) {
                        if (data.result) {
                            $('#modalReadCode #modalReadCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                            $('#modal_read_code_init_btn').click();
                        } else {
                            $('#modalErrorCode #modalErrorCodeBody').text(data.error);
                            $('#modal_code_error_btn').click();
                        }
                    }
                });
            });

            $('#modal_read_code_repeat').click(function() {
                let temp = $("#auto_sel_temp").val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                let wbMir = $("#wbMir").val();
                $.ajax({
                    url: url_read_code,
                    data: {'_token': _token, 'wbMir': wbMir, 'ip': ip},
                    success: function (data) {
                        $('#modal_received_code_close').click();
                        if (data.result) {
                            $('#modalReadCode #modalReadCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                            $('#modal_read_code_init_btn').click();
                        } else {
                            $('#modalErrorCode #modalErrorCodeBody').text(data.error);
                            $('#modal_code_error_btn').click();
                        }
                    }
                });
            });

            $('#modal_get_result_code').click(function() {
                let temp = $("#auto_sel_temp").val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                let wbMir = $("#wbMir").val();
                $.ajax({
                    url: url_recive_code,
                    data: {'_token': _token, 'wbMir': wbMir, 'ip': ip},
                    success: function (data) {
                        if (data.result) {
                            $('#modalReceivedCode #modalReceivedCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                            $('#modalReceivedCode #receivedCode').val(data.code);
                            $('#modal_received_code_init_btn').click();
                        } else {
                            $('#modalErrorCode #modalErrorCodeBody').text(data.error);
                            $('#modal_code_error_btn').click();
                        }
                    }
                });
            });

            $('#modal_save_code').click(function() {
                let temp = $("#auto_sel_temp").val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                let code = $("#receivedCode").val();
                $.ajax({
                    url: url_save_code,
                    data: {'_token': _token, 'temp': temp, 'operationMode': operationMode, 'fanMode': fanMode, 'kind': kind, 'code': code},
                    success: function (data) {
                        $('#modal_received_code_close').click();
                        if (data.result) {
                            $('#modal_code_saved_btn').click();
                            $.ajax({
                                url: url_code,
                                data: {'_token': _token, 'temp': $("#auto_sel_temp").val(), 'operationMode': $("#auto_sel_operationMode").val(), 'fanMode': $("#auto_sel_fanMode").val(), 'kind': kind,},
                                success: function (data) {
                                    $('#place').val('code');
                                    $('#dataCode').val(data.code);
                                }
                            });
                        } else {
                            $('#modalErrorCode #modalErrorCodeBody').text('Ошибка сохранения кода. Попробуйте еще раз через некоторое время');
                            $('#modal_code_error_btn').click();
                        }
                    }
                });
            });

            $('#modal_read_code_close').click(function() {
                let wbMir = $("#wbMir").val();
                $.ajax({
                    url: url_cancel_reading_code,
                    data: {'_token': _token, 'wbMir': wbMir, 'ip': ip},
                    success: function (data) {
                        if (!data.result) {
                            $('#modalErrorCode #modalErrorCodeBody').text(data.error);
                            $('#modal_code_error_btn').click();
                        }
                    }
                });
            });
        });
    </script>
@endsection
