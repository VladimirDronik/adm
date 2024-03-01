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
                                        <option value="{{ $type->id }}" data-type="{{ $type->type }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('bus', 'Шина*:', $buses, old('bus'), false, false, ['required' => true], null, null, 3, false, true) }}

                        {{ Form::bs_number('address', 'Адрес*:', old('address'), ['min' => 1, 'max' => 247, 'required' => true]) }}

                        <div id='wb_led' hidden>
                            {{ Form::bs_select('wb_led_oper_mode', 'Режим работы*:', $wbLedOperModes, old('wb_led_oper_mode'), ['required' => true]) }}
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
            $('#wb_led').removeAttr("hidden");
            $('#slaver_form input[name=wb_led_oper_mode]').removeAttr("disabled");
        } else {
            $('#wb_led').attr("hidden", true);
            $('#slaver_form input[name=wb_led_oper_mode]').attr("disabled", true);
        }

        $(document).ready(function () {
            $("#auto_sel_bus").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#slaver_form select[name=type]').change(function() {
                var selectedOption = $(this).find('option:selected');
                var type = selectedOption.data('type');

                if (type == 'wb-led') {
                    $('#wb_led').removeAttr("hidden");
                    $('#slaver_form input[name=wb_led_oper_mode]').removeAttr("disabled");
                } else {
                    $('#wb_led').attr("hidden", true);
                    $('#slaver_form input[name=wb_led_oper_mode]').attr("disabled", true);
                }
            });
        });
    </script>
@endsection
