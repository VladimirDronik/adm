@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление шины', 'links' => [ route('mod_bus.buses.index') => 'Шины']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.buses.index') }}" class="btn btn-success m-b-10 m-l-5">Шины</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'mod_bus.buses.store', 'method' => 'post', 'id' => 'bus_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_radio('type', 'Тип*:', $types, old('type', 'rtu'), ['required' => true]) }}

                        <div id='rtu_div'>
                            {{ Form::bs_select('device_select', 'Устройство*:', $devices, old('device_select'), ['required' => true]) }}

                            {{ Form::bs_select('baudrate', 'Скорость*:', $baudrates, old('baudrate', 9600), ['required' => true]) }}

                            {{ Form::bs_number('length', 'Биты данных*:', old('length', 8), ['min' => 5, 'max' => 8, 'required' => true]) }}

                            {{ Form::bs_select('parity', 'Четность*:', $parities, old('parity', 'none'), ['required' => true]) }}

                            {{ Form::bs_select('stopbits', 'Стоповые биты*:', $stopbits, old('stopbits', 1), ['required' => true]) }}
                        </div>

                        <div id='tcp_div' hidden>
                            {{ Form::bs_text('device_text', 'Устройство*:', old('device_text'), ['required' => true]) }}

                            {{ Form::bs_text('ip_address', 'IP адрес*:', old('ip_address'), ['required' => true]) }}

                            {{ Form::bs_number('port', 'Порт*:', old('port'), ['min' => 0, 'max' => 65535, 'required' => true]) }}
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
        function rtuFields() {
            $('#rtu_div').removeAttr("hidden");
            $('#bus_form input[name=device_select]').removeAttr("disabled");
            $('#bus_form input[name=baudrate]').removeAttr("disabled");
            $('#bus_form input[name=length]').removeAttr("disabled");
            $('#bus_form input[name=parity]').removeAttr("disabled");
            $('#bus_form input[name=stopbits]').removeAttr("disabled");

            $('#tcp_div').attr("hidden", true);
            $('#bus_form input[name=device_text]').attr("disabled", true);
            $('#bus_form input[name=ip_address]').attr("disabled", true);
            $('#bus_form input[name=port]').attr("disabled", true);
        }

        function tcpFields() {
            $('#tcp_div').removeAttr("hidden");
            $('#bus_form input[name=device_text]').removeAttr("disabled");
            $('#bus_form input[name=ip_address]').removeAttr("disabled");
            $('#bus_form input[name=port]').removeAttr("disabled");

            $('#rtu_div').attr("hidden", true);
            $('#bus_form input[name=device_select]').attr("disabled", true);
            $('#bus_form input[name=baudrate]').attr("disabled", true);
            $('#bus_form input[name=length]').attr("disabled", true);
            $('#bus_form input[name=parity]').attr("disabled", true);
            $('#bus_form input[name=stopbits]').attr("disabled", true);
        }

        if ($('#bus_form input[name=type]:checked').val() == 'rtu') {
            rtuFields();
        } else {
            tcpFields();
        }

        $(document).ready(function () {
            $('#bus_form input[name=type]').change(function() {
                var options = $('#bus_form input[name=type]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'rtu') {
                    rtuFields();
                } else {
                    tcpFields();
                }
            });
        });
    </script>
@endsection
