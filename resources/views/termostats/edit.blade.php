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
                    {!! Form::model($termostat, ['route' => ['termostats.update', $termostat->id],
                            'id' => 'termostat_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
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

                        @if(($termostat->iobject && $termostat->iobject->is_system) || !$can['devices.show-object'])
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="">
                                    Объект термостата:     </label>
                                <div class="col-md-9">
                                    <div class="mt-2">
                                        <a class="a-color" href="{{ route('objects.edit', [$termostat->id_object]) }}">
                                            {{ $termostat->iobject->name }} @if($termostat->iobject && $termostat->iobject->is_system) (системный) @endif </a>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id_object" value="{{ $termostat->id_object }}">
                        @else
                            {{ Form::bs_autoselect_and_btn('id_object', 'Объект термостата*:', $objects, old('id_object', $termostat->id_object),
                                false, false, ['required' => true]) }}
                        @endif

                        {{ Form::bs_autoselect_and_btn('object', 'Объект влияния:', $objects, old('object', $termostat->object),
                            false, false, [], '', '', null, 'Объект, у которого меняем состояние', 3, $can['devices.show-object']) }}

                        {{ Form::bs_autoselect('method_on', 'Метод при включении:', $methods, old('method_on', $termostat->method_on),
                            false, false, [], null, 'Метод объекта влияния при срабатывании термостата на включение') }}

                        <div class="form-group row" id="method_on_params_div"
                             @if(is_null($termostat->method_on_params) && !old('method_on')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_on_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="method_on_params_label" for="method_on_params">
                                        {{ optional($termostat->emethod_on)->params }}*:</label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="method_on_params" name="method_on_params"
                                               type="text" value="{{ old('method_on_params', $termostat->method_on_params) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('method_off', 'Метод при выключении:', $methods, old('method_off', $termostat->method_off),
                            false, false, [], null, 'Метод объекта влияния при срабатывании термостата на выключение') }}

                        <div class="form-group row" id="method_off_params_div"
                             @if(is_null($termostat->method_off_params) && !old('method_off')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 label-fix" for="method_off_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="method_off_params_label" for="method_off_params">
                                        {{ optional($termostat->emethod_off)->params }}*:
                                    </label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="method_off_params" name="method_off_params"
                                               type="text" value="{{ old('method_off_params', $termostat->method_off_params) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', is_null($termostat->room) ? 0 : $termostat->room ), false, false) }}

                    </div>
                    {{ Form::bs_submit_btn() }}

                    @include('objects.methods', ['object' => $termostat->iobject])
                    @include('objects.events', ['object' => $termostat->iobject])

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
            </div>
        </div>
    </div>
    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
    @include('components.create_object_modal', compact('object_types'))
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/termostat.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/methods.js') }}"></script>
    <script>
        const url_methods = '{{ route('ajax.objects.methods') }}';
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';
        const store_url = '{{ route('ajax.methods.store') }}';
        const del_url = '{{ route('ajax.methods.delete') }}';
        const sub_data_url = '{{ route('ajax.load.data') }}';
        const object_id = '{{ optional($termostat->iobject)->id }}';
        let del_id;
        let modal_btn_index = -1;
        let methods = [];

        $(document).ready(function () {
            initTermostatForm();
            initMethodsVar({{ optional($termostat->eobject)->id }});

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

                createObjectSelect('#auto_sel_id_object', objects,
                    modal_btn_index === 1 ? selected : $('#auto_sel_id_object').val());
                createObjectSelect('#auto_sel_object', objects,
                    modal_btn_index === 2 ? selected : $('#auto_sel_object').val());
            }

            // methods

            const cancel_btn = $('#cancel_btn');

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(clickApplyBtn);

            // edit method
            $('body').on('click', '.edit_btn', clickEditBtn);

            // change easy/script/none in modal
            $('input[type=radio][name=actions]').change(changeRadioActions);

            // delete method
            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить метод «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(clickDelBtn);
        });
    </script>
@endsection
