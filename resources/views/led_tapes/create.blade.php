@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление led ленты', 'links' => [ route('led_tapes.index') => 'Led ленты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('led_tapes.index') }}" class="btn btn-success m-b-10 m-l-5">Led ленты</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'led_tapes.store', 'method' => 'post', 'id' => 'led_tape_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_radio('type', 'Тип ленты*:', $types, old('type'), ['required' => true]) }}

                        {{ Form::bs_autoselect('device_id', 'Контроллер*:', $devices, old('device_id', ), false, false, ['required' => true], null) }}

                        <div id='port_id_div' style="display: none">
                            {{ Form::bs_autoselect('port_id', 'Порт*:', [], old('port_id'), false, false, ['required' => true], null) }}
                        </div>
                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix">
                            </label>
                            <div class="col-sm-9">
                                <ul id="portList">
                                </ul>
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
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        const url_ports = '{{ route('ajax.devices.free_wb_led_ports_by_type') }}';

        $(document).ready(function () {
            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_device_id").chosen().change(function() {
                if (!$("#led_tape_form input[name='type']:checked").val()) {
                    showErrorModal('Сначала выберите тип ленты');
                } else {
                    $("#portList").empty();
                    let device_id = $(this).val();
                    let led_type = $("input[name='type']:checked").val();
                    $.ajax({
                        url: url_ports,
                        data: {'_token': _token, 'device_id': device_id, 'types': 'out', 'led_type': led_type},
                        success: function (data) {
                            $('#port_id_div').show();
                            createPortSelect('#auto_sel_port_id', data.ports, -1);
                            createPortsList(data.ports_info);
                            $('#auto_sel_port_id').trigger("chosen:updated");
                        }
                    });
                }
            });

            $('input[name="type"]').change(function() {
                $("#portList").empty();
                let device_id = $("#auto_sel_device_id").chosen().val();
                if (device_id) {
                    let led_type = $("input[name='type']:checked").val();
                    $.ajax({
                        url: url_ports,
                        data: {'_token': _token, 'device_id': device_id, 'types': 'out', 'led_type': led_type},
                        success: function (data) {
                            $('#port_id_div').show();
                            createPortSelect('#auto_sel_port_id', data.ports, -1);
                            createPortsList(data.ports_info);
                            $('#auto_sel_port_id').trigger("chosen:updated");
                        }
                    });
                }
            });

            function createPortSelect(target, options, selected) {
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

            function createPortsList(data) {
                var portList = $("#portList");

                for (var i = 0; i < data.length; i++) {
                    var portInfo = data[i];
                    var text = portInfo.name;
                    var listItem = $("<li></li>");

                    listItem.html(text);
                    portList.append(listItem);
                }
            }

            function validateLedTape() {
                if (!$("#led_tape_form input[name='name']").val()) {
                    return 'Укажите название для ленты';
                }
                if (!$("#led_tape_form input[name='type']:checked").val()) {
                    return 'Выберите тип ленты';
                }
                if (!$("#auto_sel_device_id").chosen().val()) {
                    return 'Выберите контроллер';
                }
                if (!$("#auto_sel_port_id").chosen().val()) {
                    return 'Выберите порт/порты';
                }

                return '';
            }

            $("#led_tape_form button[type=submit]").click(function() {
                let message = validateLedTape();
                if (message !== '') {
                    showErrorModal(message);
                }
            });
        });
    </script>
@endsection
