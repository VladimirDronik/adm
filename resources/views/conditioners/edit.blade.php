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
    @include('conditioners.code_modals')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_code = '{{ route('ajax.conditioners.code') }}';
        const url_read_code = '{{ route('ajax.conditioners.read_code') }}';
        const kind = '{{ $conditionerKind->id }}';
        const ip = '{{ $conditioner->device->ip_address }}';

        $("#auto_sel_temp").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_operationMode").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_fanMode").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
        $("#auto_sel_id_room").chosen({width:"100%", no_results_text: "Не найдено"});

        $(document).ready(function () {

            $("#auto_sel_temp").chosen().change(function() {
                let temp = $(this).val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                $.ajax({
                    url: url_code,
                    data: {'_token': _token, 'temp': temp, 'operationMode': operationMode, 'fanMode': fanMode, 'kind': kind,},
                    success: function (data) {
                        $('#code_div').show();
                        $('#place').val('code');
                        createCodeField('#code', data.code);
                        $('#auto_sel_temp').trigger("chosen:updated");
                    }
                });
            });

            function createCodeField(target, code) {
                let sel = $(target);
                sel.html('');
                let s = '<input class="form-control" autocomplete="off" name="code" type="text" value="' + code + '">';
                sel.append(s);
            }

            $('#readCodeBtn').click(function() {
                let wbMir = $("#wbMir").val();
                let temp = $("#auto_sel_temp").val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                $('#modalReadCode #modalReadCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                $('#modal_read_code_init_btn').click();
                $.ajax({
                    url: url_read_code,
                    data: {'_token': _token, 'wbMir': wbMir, 'ip': ip},
                    success: function (data) {
                        $('#modal_read_code_close').click();
                        $('#modalReceivedCode #modalReceivedCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                        $('#modalReceivedCode #receivedCode').val(data.code);
                        $('#modal_received_code_init_btn').click();
                    }
                });
            });

            $('#modal_read_code_repeat').click(function() {
                let temp = $("#auto_sel_temp").val();
                let operationMode = $("#auto_sel_operationMode").val();
                let fanMode = $("#auto_sel_fanMode").val();
                let wbMir = $("#wbMir").val();
                $('#modal_received_code_close').click();
                $('#modalReadCode #modalReadCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                $('#modal_read_code_init_btn').click();
                $.ajax({
                    url: url_read_code,
                    data: {'_token': _token, 'wbMir': wbMir, 'ip': ip},
                    success: function (data) {
                        $('#modal_read_code_close').click();
                        $('#modalReceivedCode #modalReceivedCodeTitle').text('Настройка температуры ' + temp + '°С в режиме ' + operationMode + ' со скоростью ' + fanMode);
                        $('#modalReceivedCode #receivedCode').val(data.code);
                        $('#modal_received_code_init_btn').click();
                    }
                });
            });
        });
    </script>
@endsection
