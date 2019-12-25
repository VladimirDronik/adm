@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление диммера', 'links' => [ route('dimmers.index') => 'Диммеры']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('dimmers.index') }}" class="btn btn-success m-b-10 m-l-5">Список диммеров</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'dimmers.store', 'method' => 'post', 'id' => 'dimmer_form',
                        'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="id_object">
                                <strong>Объект*:</strong>
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
                                        <p>При создании диммера будет создан объект с таким же названием.
                                            У объекта будут созданы методы
                                            «Включить диммер», «Выключить диммер»,
                                            «Увеличить яркость диммера», «Уменьшить яркость диммера»,
                                            «Установить яркость диммера».
                                            У методов будут соответствующие скрипты.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_number('value', 'Значение*:', old('value'), ['required' => true]) }}
                        {{ Form::bs_number('speed', 'Скорость*:', old('speed'), ['required' => true]) }}

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
    <script src="{{ asset('ela/js/pagescripts/dimmer.js') }}"></script>
    <script src="{{ asset('ela/js/pagescripts/express_create_object.js') }}"></script>
    <script>
        const storeObjectUrl = '{{ route('ajax.objects.store') }}';

        $(document).ready(function () {
            initDimmerForm();

            $('#auto_sel_btn_id_object').click(function() {
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
                const id = $('#auto_sel_id_object').val();
                if (id) {
                    selected = id;
                }
                createObjectSelect('#auto_sel_id_object', objects, selected);
            }

            $('#dimmer_form [name=object_type]').change(function(){
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
