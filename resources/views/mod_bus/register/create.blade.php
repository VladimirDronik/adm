@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Добавление регистра',
        'links' => [ route('mod_bus.registers.index') => 'Регистры']
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.registers.index') }}" class="btn btn-success m-b-10 m-l-5">Регистры</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'mod_bus.registers.store', 'method' => 'post', 'id' => 'register_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_autoselect('slaver_id', 'Устройство*:', $slavers, old('slaver_id'), false, false, ['required' => true], null, null, 3, false, true) }}

                        {{ Form::bs_select('register_type', 'Тип*:', [], old('register_type'), ['required' => true]) }}

                        {{ Form::bs_number('starting_register', 'Начальный адрес*:', old('starting_register'), ['min' => 0, 'max' => 65535, 'required' => true]) }}

                        <div id='registers_quantity' hidden>
                            {{ Form::bs_number('registers_quantity', 'Кол-во регистров*:', old('registers_quantity'), ['min' => 1, 'max' => 125, 'required' => true]) }}
                        </div>

                        {{ Form::bs_select('data_format', 'Формат данных*:', $dataFormats, old('data_format'), ['required' => true]) }}

                        {{ Form::bs_text('units', 'Единица измерения:', old('units'), []) }}

                        {{ Form::bs_text('scale_unit', 'Множитель:', old('scale_unit'), []) }}

                        {{ Form::bs_radio('access', 'Доступ*:', $accesses, old('access'), ['required' => true]) }}

                        <div id='alias' hidden>
                            {{ Form::bs_text('alias', 'Алиас:', old('alias'), []) }}
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
        $(document).ready(function () {
            $("#auto_sel_slaver_id").chosen({width:"100%", no_results_text: "Не найдено"});

            function updateRegisterTypeSelect(protocol) {
                var select = $('#register_form select[name=register_type]');
                select.empty();

                var options = [];
                if (protocol === 'modbus') {
                    options = [
                        { value: 'coil', text: 'coil' },
                        { value: 'holding', text: 'holding' },
                        { value: 'input', text: 'input' },
                        { value: 'discrete', text: 'discrete' }
                    ];
                } else if (protocol === 'pulsarm') {
                    options = [
                        { value: 'channel', text: 'channel' },
                        { value: 'parameter', text: 'parameter' }
                    ];
                }

                $.each(options, function (index, option) {
                    select.append(new Option(option.text, option.value));
                });
            }

            if ($("#auto_sel_slaver_id").chosen().val()) {
                $.ajax({
                    url: "{{ route('ajax.mod_bus.slavers.additional_data') }}",
                    data: {'_token': _token, 'slaver_id': $("#auto_sel_slaver_id").chosen().val()},
                    success: function (data) {
                        updateRegisterTypeSelect(data.protocol);

                        if (data.protocol == 'modbus') {
                            $('label[for="starting_register"] strong').text('Начальный адрес*:');
                            $('#registers_quantity').removeAttr("hidden");
                            $('#register_form input[name=registers_quantity]').removeAttr("disabled");
                        } else if (data.protocol == 'pulsarm') {
                            $('label[for="starting_register"] strong').text('Адрес*:');
                            $('#registers_quantity').attr("hidden", true);
                            $('#register_form input[name=registers_quantity]').attr("disabled", true);
                        }

                        if (data.is_custom) {
                            $('#alias').removeAttr("hidden");
                            $('#register_form input[name=alias]').removeAttr("disabled");
                        } else {
                            $('#alias').attr("hidden", true);
                            $('#register_form input[name=alias]').attr("disabled", true);
                        }
                    }
                });
            }

            $("#auto_sel_slaver_id").chosen().change(function() {
                let slaver_id = $(this).val();
                $.ajax({
                    url: "{{ route('ajax.mod_bus.slavers.additional_data') }}",
                    data: {'_token': _token, 'slaver_id': slaver_id},
                    success: function (data) {
                        updateRegisterTypeSelect(data.protocol);

                        if (data.protocol == 'modbus') {
                            $('label[for="starting_register"] strong').text('Начальный адрес*:');
                            $('#registers_quantity').removeAttr("hidden");
                            $('#register_form input[name=registers_quantity]').removeAttr("disabled");
                        } else if (data.protocol == 'pulsarm') {
                            $('label[for="starting_register"] strong').text('Адрес*:');
                            $('#registers_quantity').attr("hidden", true);
                            $('#register_form input[name=registers_quantity]').attr("disabled", true);
                        }

                        if (data.is_custom) {
                            $('#alias').removeAttr("hidden");
                            $('#register_form input[name=alias]').removeAttr("disabled");
                        } else {
                            $('#alias').attr("hidden", true);
                            $('#register_form input[name=alias]').attr("disabled", true);
                        }
                    }
                });
            });
        });
    </script>
@endsection
