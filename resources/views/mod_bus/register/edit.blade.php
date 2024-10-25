@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование регистра № '. $register->id,
        'links' => [ route('mod_bus.registers.index') => 'Регистры'],
        'last_link' => 'Редактирование регистра'
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.registers.index') }}" class="btn btn-success m-b-10 m-l-5">Регистры</a>
                        <a href="{{ route('mod_bus.registers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить регистр</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($register, ['route' => ['mod_bus.registers.update', $register->id], 'id' => 'register_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $register->name), ['required' => true]) }}

                        {{ Form::bs_autoselect('slaver_id', 'Устройство*:', $slavers, old('slaver_id', $register->slaver_id), false, false, ['required' => true], null, null, 3, false, true) }}

                        {{ Form::bs_select('register_type', 'Тип*:', $types, old('register_type', $register->register_type), ['required' => true]) }}

                        {{ Form::bs_number('starting_register', 'Начальный адрес*:', old('starting_register', $register->starting_register), ['min' => 0, 'max' => 65535, 'required' => true]) }}

                        {{ Form::bs_number('registers_quantity', 'Кол-во регистров*:', old('registers_quantity', $register->registers_quantity), ['min' => 1, 'max' => 100, 'required' => true]) }}

                        {{ Form::bs_select('data_format', 'Формат данных*:', $dataFormats, old('data_format', $register->data_format), ['required' => true]) }}

                        {{ Form::bs_text('units', 'Единица измерения:', old('units', $register->units), []) }}

                        {{ Form::bs_text('scale_unit', 'Множитель:', old('scale_unit', $register->scale_unit), []) }}

                        {{ Form::bs_radio('access', 'Доступ*:', $accesses, old('access', $register->access), ['required' => true]) }}

                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" for="access">Значение регистра:</label>
                            <div class="col-md-9">
                                <label class="col-md-2" id="last_value">{{ $register->last_value ?: 'Нет' }}</label>
                                <button type="button" class="btn btn-success m-b-10 m-l-5" id="modbusRead">Обновить</button>
                            </div>
                        </div>
                        <div id='modbus_write_div'>
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="access">Записать значение:</label>
                                <div class="col-md-9">
                                    <input class="form-control col-md-2" style="display: inline;" autocomplete="off" id="" name="modbus_write" type="text" value="">
                                    <button type="button" class="btn btn-success m-b-10 m-l-5" id="modbusWrite">Записать</button>
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
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        let modbus_read_url = '{{ route('ajax.mod_bus.registers.read') }}';
        let modbus_write_url = '{{ route('ajax.mod_bus.registers.write') }}';
        let id = '{{ $register->id }}';

        var access = $('#register_form input[name=access]:checked').val();
        if (access == 'ro') {
            $('#modbus_write_div').attr("hidden", true);
        } else {
            $('#modbus_write_div').removeAttr("hidden");
        }

        $(document).ready(function () {
            $("#auto_sel_slaver_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#register_form input[name=access]').change(function() {
                var options = $('#register_form input[name=access]');
                for (var i = 0; i < options.length; i++) {
                    if (options[i].checked) {
                        var selectedOption = options[i].value;
                    }
                }

                if (selectedOption == 'ro') {
                    $('#modbus_write_div').attr("hidden", true);
                } else {
                    $('#modbus_write_div').removeAttr("hidden");
                }
            });

            $('#modbusRead').click(function() {
                $.ajax({
                    url: modbus_read_url,
                    data: { '_token': _token, 'id': id },
                    success: function (data) {
                        if (data.result) {
                            $('#last_value').text(data.response);
                        } else {
                            showErrorModal(data.response ?? 'Нет ответа от устройства');
                        }
                    }
                });
            });

            $('#modbusWrite').click(function() {
                var value = $('#register_form input[name=modbus_write]').val();

                if (value) {
                    $.ajax({
                        url: modbus_write_url,
                        data: { '_token': _token, 'id': id, 'value': value },
                    });
                }
            });
        });
    </script>
@endsection
