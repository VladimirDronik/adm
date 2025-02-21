@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление устройства', 'links' => [ route('mod_bus.slavers.index') => 'Устройства']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.slavers.index') }}" class="btn btn-success m-b-10 m-l-5">Устройства</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'mod_bus.slavers.store', 'method' => 'post', 'id' => 'slaver_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="type">
                                <strong>Тип*:</strong>
                            </label>
                            <div class="col-md-9">
                                <select class="form-control line-select" autocomplete="off" required="" name="type">
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" data-type="{{ $type->type }}" data-protocol="{{ $type->protocol }}">{{ $type->name }}</option>
                                    @endforeach
                                    <option value="custom" data-type="custom">Custom</option>
                                </select>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('bus', 'Шина*:', $buses, old('bus'), false, false, ['required' => true], null, null, 3, false, true) }}

                        {{ Form::bs_number('address', 'Адрес*:', old('address'), ['required' => true]) }}

                        <div id='purpose' hidden>
                            {{ Form::bs_select('purpose', 'Назначение устройства*:', $purposes, old('purpose'), ['required' => true]) }}
                        </div>

                        <div id='wb_led' hidden>
                            {{ Form::bs_select('wb_led_oper_mode', 'Режим работы*:', $wbLedOperModes, old('wb_led_oper_mode'), ['required' => true]) }}
                        </div>

                        <div id='protocol' hidden>
                            {{ Form::bs_select('protocol', 'Протокол*:', $protocols, old('protocol'), ['required' => true]) }}
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
        if ($('#slaver_form select[name=type]').find('option:selected').data('type') == 'wb-led') {
            $('#purpose').attr("hidden", true);
            $('#slaver_form input[name=purpose]').attr("disabled", true);
            $('#protocol').attr("hidden", true);
            $('#slaver_form input[name=protocol]').attr("disabled", true);
            $('#wb_led').removeAttr("hidden");
            $('#slaver_form input[name=wb_led_oper_mode]').removeAttr("disabled");
        } else if ($('#slaver_form select[name=type]').find('option:selected').data('type') == 'custom') {
            if ($('#slaver_form select[name=protocol]').find('option:selected').val() == 'modbus') {
                $('#slaver_form input[name=address]').attr("min", 1);
                $('#slaver_form input[name=address]').attr("max", 247);
            } else if ($('#slaver_form select[name=protocol]').find('option:selected').val() == 'pulsarm') {
                $('#slaver_form input[name=address]').removeAttr("min");
                $('#slaver_form input[name=address]').removeAttr("max");
            }

            $('#purpose').removeAttr("hidden");
            $('#slaver_form input[name=purpose]').removeAttr("disabled");
            $('#protocol').removeAttr("hidden");
            $('#slaver_form input[name=protocol]').removeAttr("disabled");
            $('#wb_led').attr("hidden", true);
            $('#slaver_form input[name=wb_led_oper_mode]').attr("disabled", true);
        } else {
            $('#purpose').attr("hidden", true);
            $('#slaver_form input[name=purpose]').attr("disabled", true);
            $('#wb_led').attr("hidden", true);
            $('#slaver_form input[name=wb_led_oper_mode]').attr("disabled", true);
            $('#protocol').attr("hidden", true);
            $('#slaver_form input[name=protocol]').attr("disabled", true);
        }

        if ($('#slaver_form select[name=type]').find('option:selected').data('protocol') == 'modbus') {
            $('#slaver_form input[name=address]').attr("min", 1);
            $('#slaver_form input[name=address]').attr("max", 247);
        } else if ($('#slaver_form select[name=type]').find('option:selected').data('protocol') == 'pulsarm') {
            $('#slaver_form input[name=address]').removeAttr("min");
            $('#slaver_form input[name=address]').removeAttr("max");
        }

        $(document).ready(function () {
            $("#auto_sel_bus").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#slaver_form select[name=type]').change(function() {
                var selectedOption = $(this).find('option:selected');
                var type = selectedOption.data('type');
                var protocol = selectedOption.data('protocol');

                if (type == 'wb-led') {
                    $('#purpose').attr("hidden", true);
                    $('#slaver_form input[name=purpose]').attr("disabled", true);
                    $('#protocol').attr("hidden", true);
                    $('#slaver_form input[name=protocol]').attr("disabled", true);
                    $('#wb_led').removeAttr("hidden");
                    $('#slaver_form input[name=wb_led_oper_mode]').removeAttr("disabled");
                } else if (type == 'custom') {
                    var protocol = $('#slaver_form select[name=protocol]').find('option:selected').val();

                    $('#purpose').removeAttr("hidden");
                    $('#slaver_form input[name=purpose]').removeAttr("disabled");
                    $('#protocol').removeAttr("hidden");
                    $('#slaver_form input[name=protocol]').removeAttr("disabled");
                    $('#wb_led').attr("hidden", true);
                    $('#slaver_form input[name=wb_led_oper_mode]').attr("disabled", true);
                } else {
                    $('#purpose').attr("hidden", true);
                    $('#slaver_form input[name=purpose]').attr("disabled", true);
                    $('#wb_led').attr("hidden", true);
                    $('#slaver_form input[name=wb_led_oper_mode]').attr("disabled", true);
                    $('#protocol').attr("hidden", true);
                    $('#slaver_form input[name=protocol]').attr("disabled", true);
                }

                if (protocol == 'modbus') {
                    $('#slaver_form input[name=address]').attr("min", 1);
                    $('#slaver_form input[name=address]').attr("max", 247);
                } else if (protocol == 'pulsarm') {
                    $('#slaver_form input[name=address]').removeAttr("min");
                    $('#slaver_form input[name=address]').removeAttr("max");
                }
            });

            $('#slaver_form select[name=protocol]').change(function() {
                var protocol = $(this).find('option:selected').val();

                if (protocol == 'modbus') {
                    $('#slaver_form input[name=address]').attr("min", 1);
                    $('#slaver_form input[name=address]').attr("max", 247);
                } else if (protocol == 'pulsarm') {
                    $('#slaver_form input[name=address]').removeAttr("min");
                    $('#slaver_form input[name=address]').removeAttr("max");
                }
            });
        });
    </script>
@endsection
