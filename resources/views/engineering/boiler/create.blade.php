@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Инженерное оборудование'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('engineering.index') }}" class="btn btn-success m-b-10 m-l-5"> <i class="fa fa-reply-all" aria-hidden="true"></i> Все устройства</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Добавление котла</h4></div>
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'boiler.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered', 'id' => 'engineering_form']) !!}
                        {{ csrf_field() }}

                            <div class="form-body">
                                {{ Form::bs_alert() }}

                                {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                                {{ Form::bs_radio('gateway_type', 'Тип подключения*:', $gatewayTypes, old('gateway_type', 'modbus'), ['required' => true]) }}

                                <div id='http_div' hidden>
                                    {{ Form::bs_autoselect('http_gateway_id', 'Контроллер*:', $devices, old('http_gateway_id'), false, false, ['required' => true], null, null, 3, false, true) }}
                                </div>

                                <div id='modbus_div' hidden>
                                    {{ Form::bs_autoselect('modbus_gateway_id', 'Устройство*:', $modbusSlavers, old('modbus_gateway_id'), false, false, ['required' => true], null, null, 3, false, true) }}
                                </div>

                                {{ Form::bs_autoselect('type_boiler', 'Протокол обмена*:', $typesBoiler, old('type'), false, false, ['required' => true], null) }}

                                {{ Form::bs_autoselect('id_outside_thermostat', 'Уличный датчик температуры:', $termostats, old('id_outside_thermostat'), false, false, [], null) }}
                            </div>

                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        if ($('#engineering_form input[name=gateway_type]:checked').val() == 'modbus') {
            $('#modbus_div').removeAttr("hidden");
            $('#engineering_form input[name=modbus_gateway_id]').removeAttr("disabled");

            $('#http_div').attr("hidden", true);
            $('#engineering_form input[name=http_gateway_id]').attr("disabled", true);
        } else {
            $('#http_div').removeAttr("hidden");
            $('#engineering_form input[name=http_gateway_id]').removeAttr("disabled");

            $('#modbus_div').attr("hidden", true);
            $('#engineering_form input[name=modbus_gateway_id]').attr("disabled", true);
        }

        $(document).ready(function(){
            $("#auto_sel_id_outside_thermostat").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_http_gateway_id").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_modbus_gateway_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#engineering_form input[name=gateway_type]').change(function() {
                var options = $('#engineering_form input[name=gateway_type]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'modbus') {
                    $('#modbus_div').removeAttr("hidden");
                    $('#engineering_form input[name=modbus_gateway_id]').removeAttr("disabled");

                    $('#http_div').attr("hidden", true);
                    $('#engineering_form input[name=http_gateway_id]').attr("disabled", true);
                } else {
                    $('#http_div').removeAttr("hidden");
                    $('#engineering_form input[name=http_gateway_id]').removeAttr("disabled");

                    $('#modbus_div').attr("hidden", true);
                    $('#engineering_form input[name=modbus_gateway_id]').attr("disabled", true);
                }
            });
        });
    </script>
@endsection
