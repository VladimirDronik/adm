@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление термостата', 'links' => [ route('termostats.index') => 'Термостаты']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('termostats.index') }}" class="btn btn-success m-b-10 m-l-5">Список термостатов</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'termostats.store', 'method' => 'post',
                            'id' => 'termostat_form', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_text('id_termometr', 'Код:', null, [], 'Например, ff750c311703') }}
                            {{ Form::bs_number('optimal', 'Оптимальная температура*:', null, ['min' => 0, 'max' => 40, 'required' => true],
                                'Температура, которая должна быть в помещении') }}
                            {{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 1), ['min' => 0, 'max' => 10, 'required' => true]) }}
                            {{ Form::bs_radio('thermostat', 'Режим*:', $types, old('thermostat', -1), ['required' => true]) }}

                            {{ Form::bs_number('min_threshold', 'Минимальная температура*:', old('min_threshold', 0), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}
                            {{ Form::bs_number('max_threshold', 'Максимальная температура*:', old('max_threshold', 30), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}
                            {{ Form::bs_number('min_alarm', 'Мин. аварийная температура*:', old('min_alarm', 0), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}
                            {{ Form::bs_number('max_alarm', 'Макс. аварийная температура*:', old('max_alarm', 40), ['min' => 0, 'max' => 40, 'required' => true],
                                '') }}


                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                    <strong>Объект термостата*:</strong>
                                </label>
                                <div class="col-sm-9">
                                    <div class="form-group row">
                                        <div class="col-md-12 p-0">
                                            <div class="btn-group-toggle" data-toggle="buttons">
                                                <label class="btn btn-success btn-sm active">
                                                    <input type="radio" name="object_type" autocomplete="off" checked value="auto"> Создать автоматически
                                                </label>
                                                <label class="btn btn-success btn-sm">
                                                    <input type="radio" name="object_type" autocomplete="off" value="manual">  Выбор из списка
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="manual_object_div" style="display: none;">
                                        <div class="col-sm-11 pr-0">
                                            <select autocomplete="off" id="auto_sel_id_object"
                                                    data-placeholder="не выбрано"
                                                    name="id_object"
                                                    class="chosen-select form-control"
                                                    style="width:350px;">
                                                <option value="">Не выбрано</option>
                                                @foreach ($objects as $key => $value)
                                                    <option value="{{ $key }}" @if($key == old('id_object')) selected @endif>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-1 pt-1 text-left">
                                            <button type="button" id="auto_sel_btn_id_object" class="btn btn-default btn-sm" title=" Создать объект ">
                                                <i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="row" id="auto_object_div">
                                        <div class="col-sm-11 pr-0">
                                            <p>При создании термостата будет создан объект с таким же названием.
                                                У объекта будет создан метод «Проверка термостата».
                                                Для метода будет создано событие «Проверка термостата» (каждые 5 мин).
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ Form::bs_autoselect_and_btn('object', 'Объект влияния*:', $objects, old('object'),
                                false, false, ['required' => true], '', '', null, 'Объект, у которого меняем состояние') }}

                            {{ Form::bs_autoselect('method_on', 'Метод при включении*:', [], old('method_on'),
                                false, false, ['required' => true], null, 'Метод объекта влияния при срабатывании термостата на включение') }}

                            <div class="form-group row" id="method_on_params_div"
                                 @if(!old('method_on')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_on_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_on_params_label" for="method_on_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_on_params" name="method_on_params"
                                                   type="text" value="{{ old('method_on_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ Form::bs_autoselect('method_off', 'Метод при выключении*:', [], old('method_off'),
                                false, false, ['required' => true], null, 'Метод объекта влияния при срабатывании термостата на выключение') }}

                            <div class="form-group row" id="method_off_params_div"
                                 @if(!old('method_off')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 label-fix" for="method_off_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row ">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_off_params_label" for="method_off_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_off_params" name="method_off_params"
                                                   type="text" value="{{ old('method_off_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}

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
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/termostat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        let modal_btn_index = -1;
        let methods = [];

        $(document).ready(function () {
            initTermostatForm();

            $('#auto_sel_btn_id_object').click(function() {
                modal_btn_index = 1;
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#auto_sel_btn_object').click(function() {
                modal_btn_index = 2;
                clearCreateObjectModal();
                $('#create_object_modal_init_btn').click();
                return false;
            });

            $('#create_object_modal_btn').click(function() {
                let message = validateCreateObject();
                if (message !== '') {
                    showCreateObjectError(message);
                    return false;
                }

                storeObject();
            });

            function storeObject() {
                const name = $("#create_object_modal input[name=object_name]").val().trim();
                const type = $("#create_object_modal input[name=object_type]:checked").val().trim();

                $.ajax({
                    url: storeObjectUrl,
                    data: {'_token': _token, 'name': name, 'type': type},
                    success: function (data) {
                        if (data.result) {
                            hideCreateObjectError();
                            updateObjectSelects(data.objects, data.id);
                            $('#create_object_cancel_btn').click();
                        } else {
                            showCreateObjectError(data.message);
                        }
                    },
                    error: function () {
                        showCreateObjectError('Сервер временно недоступен');
                    }
                });
            }

            function updateObjectSelects(objects, selected) {
                let id = false;

                if (modal_btn_index === 1) {
                    id = $('#auto_sel_id_object').val();
                } else if (modal_btn_index === 2) {
                    id = $('#auto_sel_object').val();
                }

                if (id) {
                    selected = id;
                }

                createObjectSelect('#auto_sel_id_object', objects, modal_btn_index === 1 ? selected : $('#auto_sel_id_object').val());
                createObjectSelect('#auto_sel_object', objects, modal_btn_index === 2 ? selected : $('#auto_sel_object').val());
            }

            $('#termostat_form [name=object_type]').change(function(){
                if ($(this).val() === 'manual') {
                    $('#auto_object_div').hide();
                    $('#manual_object_div').show();
                } else {
                    $('#manual_object_div').hide();
                    $('#auto_object_div').show();
                }
                return true;
            });
        });
    </script>
@endsection
