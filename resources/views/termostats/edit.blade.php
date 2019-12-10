@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование термостата № '. $termostat->id,
        'links' => [ route('termostats.index') => 'Термостаты'],
        'last_link' => 'Редактирование термостата'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('termostats.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок термостатов</a>
                        <a href="{{ route('termostats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить термостат</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($termostat, ['route' => ['termostats.update', $termostat->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID:', $termostat->id) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_text('id_termometr', 'Код:', null, [], 'Например, ff750c311703') }}
                        {{ Form::bs_number('optimal', 'Оптимальная температура*:', null, ['min' => 0, 'max' => 40, 'required' => true],
                            'Температура, которая должна быть в помещении') }}
                        {{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $termostat->gisteresis), ['min' => 0, 'max' => 10, 'required' => true]) }}
                        {{ Form::bs_radio('thermostat', 'Режим*:', $types, old('thermostat', $termostat->thermostat), ['required' => true]) }}

                        {{ Form::bs_number('min_threshold', 'Минимальная температура*:', old('min_threshold', $termostat->min_threshold), ['min' => 0, 'max' => 40, 'required' => true],
                            '') }}
                        {{ Form::bs_number('max_threshold', 'Максимальная температура*:', old('max_threshold', $termostat->max_threshold), ['min' => 0, 'max' => 40, 'required' => true],
                            '') }}
                        {{ Form::bs_number('min_alarm', 'Мин. аварийная температура*:', old('min_alarm', $termostat->min_alarm), ['min' => 0, 'max' => 40, 'required' => true],
                            '') }}
                        {{ Form::bs_number('max_alarm', 'Макс. аварийная температура*:', old('max_alarm', $termostat->max_alarm), ['min' => 0, 'max' => 40, 'required' => true],
                            '') }}

                        {{ Form::bs_autoselect_and_btn('id_object', 'Объект термостата*:', $objects, old('id_object', $termostat->id_object),
                            false, false, ['required' => true]) }}
                        {{ Form::bs_autoselect_and_btn('object', 'Объект влияния*:', $objects, old('object', $termostat->object),
                            false, false, ['required' => true], '', '', null, 'Объект, у которого меняем состояние') }}

                        {{ Form::bs_autoselect('method_on', 'Метод при включении*:', $methods, old('method_on', $termostat->method_on),
                            false, false, ['required' => true], null, 'Метод объекта влияния при срабатывании термостата на включение') }}
                        {{ Form::bs_autoselect('method_off', 'Метод при выключении*:', $methods, old('method_off', $termostat->method_off),
                            false, false, ['required' => true], null, 'Метод объекта влияния при срабатывании термостата на выключение') }}

                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', is_null($termostat->room) ? -1 : $termostat->room ), false, false) }}

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
        });
    </script>
@endsection
