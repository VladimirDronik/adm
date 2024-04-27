@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Добавление I2C датчика',
        'links' => [ route('usensors.index') => 'I2C датчики']
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('usensors.index') }}" class="btn btn-success m-b-10 m-l-5">Список I2C датчиков</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'usensors.store', 'method' => 'post', 'id' => 'usensor_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            {{ Form::bs_radio('type', 'Тип*:', $types, old('type')) }}

                            {{ Form::bs_autoselect('device_id', 'Контроллер*:', $devices, old('device_id'), false, false, [], null) }}

                            {{ Form::bs_autoselect('port_SCL', 'Порт SCL*:', [], old('SCL'), false, false, [], null) }}

                            {{ Form::bs_autoselect('port_SDA', 'Порт SDA*:', [], old('SDA'), false, false, [], null) }}

                            {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room'), false, false, [], null) }}
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
        const url_ports = '{{ route('ajax.devices.objects_ports') }}';

        function createPortSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id) {
                    s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                } else {
                    s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
                }
            }
            sel.append(s);
        }

        $(document).ready(function () {
            $("#auto_sel_device_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SCL").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_port_SDA").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#usensor_form').submit(function(e) {
                if ($("#usensor_form input[name=type]").length && !$("#usensor_form input[name=type]:checked").val()) {
                    $('#info_modal_body').html('<span class="text-danger">Не указан тип датчика</span>');
                    $('#init_btn').click();
                    return false;
                }
            });

            $("#auto_sel_device_id").chosen().change(function() {
                let device_id = $(this).val();
                $.ajax({
                    url: url_ports,
                    data: {'_token': _token, 'device_id': device_id, 'status': 'IN,I2C,1WIRE,1W-BUS,ADC'},
                    success: function (data) {
                        createPortSelect('#auto_sel_port_SCL', data.ports, -1);
                        $('#auto_sel_port_SCL').trigger("chosen:updated");

                        createPortSelect('#auto_sel_port_SDA', data.ports, -1);
                        $('#auto_sel_port_SDA').trigger("chosen:updated");
                    }
                });
            });
        });
    </script>
@endsection
