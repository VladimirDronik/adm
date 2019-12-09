@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование объекта «'. $object->name .'»',
        'links' => [ route('objects.index') => 'Объекты'],
        'last_link' => 'Редактирование объекта'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('objects.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок объектов</a>
                        <a href="{{ route('objects.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить объект</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-10 col-xl-9">
                    {!! Form::model($object, ['route' => ['objects.update', $object->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}
                        {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type', $object->type), ['required' => true]) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        {{ Form::bs_submit_btn() }}

                        {{ Form::bs_title('Методы объекта') }}
                            <div class="form-group row">
                                <label class="col-md-3"><i>Название метода</i></label>
                                <div class="col-md-3"><i>Простое действие</i></div>
                                <div class="col-md-2"><i>Скрипт</i></div>
                                <div class="col-md-2"><i>Комментарий</i></div>
                                <div class="col-md-2 text-right"></div>
                            </div>
                            <div id="methods_div">
                                @foreach($object->methods as $method)
                                <div class="form-group row" id="div{{$method->id}}">
                                    <label class="col-md-3" id="name{{$method->id}}">
                                        {{$method->name}}
                                    </label>
                                    <div class="col-md-3" id="easy{{$method->id}}">
                                        {{ $method->easy }}
                                    </div>
                                    <div class="col-md-2" id="script{{$method->id}}">
                                        {{ optional($method->escript)->name }}
                                    </div>
                                    <div class="col-md-2" id="comment{{$method->id}}">
                                        {{ $method->comment }}
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" data-id="{{ $method->id }}"
                                                data-type="{{ $method->type }}"
                                                data-script-id="{{ $method->script }}"
                                                data-device="{{ $method->device_id }}"
                                                data-port="{{ $method->port }}"
                                                data-action="{{ $method->action }}"
                                                class="btn btn-info btn-sm btn-rounded edit_btn">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </button>
                                        <button type="button" data-id="{{ $method->id }}" data-name="{{ $method->name }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 text-left">
                                    <button id="add_btn" type="button" class="btn btn-primary">
                                        <i class="fa fa-plus fa-lg"></i> Добавить метод
                                    </button>
                                </div>
                            </div>
                        <br>
                        {{ Form::bs_title('Cобытия объекта') }}
                        @if(count($object->scheduler_tasks))
                            <div class="form-group row">
                                <label class="col-md-3"><i>Событие</i></label>
                                <div class="col-md-3"><i>Метод</i></div>
                            </div>
                            <div id="events_div">
                                @foreach($object->scheduler_tasks as $scheduler_task)
                                    <div class="form-group row" id="ediv{{$scheduler_task->method}}">
                                        <label class="col-md-3">
                                            <a href="{{ route('events.edit', [$scheduler_task->id]) }}">{{ $scheduler_task->name }}</a>
                                        </label>
                                        <div class="col-md-3">
                                            {{ optional($scheduler_task->emethod)->name }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <i>Отсутствуют</i>
                        @endif
                    </div>
                    {!! Form::close() !!}
                </div>

                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>

            </div>
        </div>
    </div>

    <div id="method_modal" class="modal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="method_modal_title">Добавление метода</h4>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger alert-dismissible fade show" id="error_div" style="display: none;">
                         <span id="error_text"></span>
                    </div>
                    <input type="hidden" id="m_id" name="m_id" value="">
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="m_name">
                            <strong>Название*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input class="form-control" required name="m_name" type="text" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix">&nbsp;
                        </label>
                        <div class="col-md-9">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-success active" id="easy_button">
                                    <input type="radio" name="actions" checked autocomplete="off" value="easy"> Простое действие
                                </label>
                                <label class="btn btn-success" id="script_button">
                                    <input type="radio" name="actions"  autocomplete="off" value="script"> Скрипт
                                </label>
                                <label class="btn btn-success" id="none_button">
                                    <input type="radio" name="actions"  autocomplete="off" value="none"> Отсутствует
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" id="easy_div">
                        <label class="control-label text-right col-md-3 label-fix"></label>
                        <div class="col-md-9">
                            <button type="button" class="btn btn-success m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="dev_select_button" onclick="loadSubData('device');">Контроллер: <span id="easy_device">отсутствует</span></button>&nbsp;
                            <button type="button" class="btn btn-success m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="port_btn" onclick="loadSubData('port');">Порт: <span id="easy_port">отсутствует</span></button>&nbsp;
                            <button type="button" class="btn btn-success m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="action_btn" onclick="loadSubData('action');">Действие: <span id="easy_action">отсутствует</span></button>
                        </div>
                    </div>
                    <div class="form-group row" id="script_div" style="display: none;">
                        <label class="control-label text-right col-md-3 label-fix" for="m_script">
                            <strong>Скрипт*:</strong>
                        </label>
                        <div class="col-md-9">
                            <select name="m_script" autocomplete="off" class="form-control">
                                <option value="">Не указан</option>
                                @foreach($scripts as $key => $script)
                                    <option value="{{ $key }}">{{ $script }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="m_comment">
                            Комментарий:
                        </label>
                        <div class="col-md-9">
                            <input class="form-control" name="m_comment" type="text" value="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="apply_btn">Добавить метод</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_btn">Отмена</button>
                </div>
            </div>
        </div>
    </div>

    <div id="methodsModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title_action"></h4>
                </div>
                <div class="modal-body" id="method_data">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary" >Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>

    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        let store_url = '{{ route('ajax.methods.store') }}';
        let del_url = '{{ route('ajax.methods.delete') }}';
        let sub_data_url = '{{ route('ajax.load.data') }}';
        let object_id = '{{ $object->id }}';
        let del_id;

        function loadSubData(mode, object_id) {
            let device = {};

            if (mode == 'port') {
                device = $('#easy_device').text().trim();
            }

            if (device == 'отсутствует') {
                alert('Сначала необходимо выбрать контроллер');
                mode = 'device';
            }

            let data = {};
            data['mode'] = mode;
            data['device'] = device;
            data['object_id'] = object_id;

            $.ajax({
                url: sub_data_url,
                data: data,
                success: function (data) {
                    $('#method_data').html(data.html);
                    $('#title_action').html(data.title_action);
                }
            });
        }

        $(document).ready(function () {
            let init_btn = $('#init_btn');
            let cancel_btn = $('#cancel_btn');

            function showModalError(message) {
                $('#error_text').text(message);
                $('#error_div').show();
            }

            function clearModal() {
                $('#easy_device').text('отсутствует');
                $('#easy_port').text('отсутствует');
                $('#easy_action').text('отсутствует');
                $("input[name=actions][value=none]").prop("checked",true);
                $("#none_button").removeClass("active");
                $("#script_button").removeClass("active");
                $("#easy_button").removeClass("active");
                $('#easy_div').hide();
                $('#script_div').hide();
                $('#error_div').hide();
            }

            function showAddModal() {
                $('#m_id').val('');
                clearModal();
                $("#none_button").addClass("active");
                $('input[name=m_name]').val('');
                $('input[name=m_comment]').val('');
                $('#method_modal_title').text('Добавление метода');
                $('#apply_btn').text('Добавить метод');
                init_btn.click();
            }

            function showEditModal(data) {
                clearModal();

                $('#m_id').val(data.id);
                $('#method_modal_title').text('Редактирование метода');
                $('#apply_btn').text('Сохранить изменения');

                $('input[name=m_name]').val($('#name'+data.id).text().trim());
                $('input[name=m_comment]').val($('#comment'+data.id).text().trim());
                if (data.type === 'script') {
                    $('select[name=m_script]').val(data.script_id);
                    $("input[name=actions][value=script]").prop("checked",true);
                    $("#script_button").addClass("active");
                    $('#script_div').show();
                } else if (data.type === 'easy') {
                    $("input[name=actions][value=easy]").prop("checked",true);
                    $("#easy_button").addClass("active");
                    $('#easy_div').show();
                    $('#easy_device').text(data.device_id);
                    $('#easy_port').text(data.port);
                    $('#easy_action').text(data.action);
                } else if (data.type === 'none') {
                    $("input[name=actions][value=none]").prop("checked",true);
                    $("#none_button").addClass("active");
                }


                init_btn.click();
            }

            function addMethod(data) {
                let html = `
                    <div class="form-group row" id="div${data.id}">
                         <label class="col-md-3" id="name${data.id}">${data.name}</label>
                         <div class="col-md-3" id="easy${data.id}">${data.easy}</div>
                         <div class="col-md-2" id="script${data.id}">${data.script_name}</div>
                         <div class="col-md-2" id="comment${data.id}">${data.comment}</div>
                         <div class="col-md-2 text-right">
                             <button type="button" data-id="${data.id}"
                                    data-type="${data.type}"
                                    data-script-id="${data.script_id}"
                                    data-device="${data.device_id}"
                                    data-port="${data.port}"
                                    data-action="${data.action}"
                                    class="btn btn-info btn-sm btn-rounded edit_btn">
                                                <i class="fa fa-cog fa-lg"></i></button>
                             <button type="button" data-id="${data.id}" data-name="${data.name}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                                <i class="fa fa-trash fa-lg"></i></button>
                         </div>
                    </div>`;

                $('#methods_div').append(html);
            }

            function editMethod(data) {
                $('#name'+data.id).text(data.name);
                $('#script'+data.id).text(data.script_name);
                $('#comment'+data.id).text(data.comment);
                $('#easy'+data.id).text(data.easy);
            }

            $('#add_btn').click(showAddModal);

            function validateMethod(data) {
                if (data.name == '') {
                    return 'Не указано название';
                }

                if (data.type === 'script' && data.script_id == "") {
                    return 'Не указан скрипт';
                }

                if (data.type === 'easy') {
                    if (data.device_id === 'отсутствует') {
                        return 'Не указан контроллер';
                    }
                    if (data.port === 'отсутствует') {
                        return 'Не указан порт';
                    }
                    if (data.action === 'отсутствует') {
                        return 'Не указано действие';
                    }
                }

                return '';
            }

            function getModalData() {
                let data = {};

                data.object_id = object_id;
                data.id = $('input[name=m_id]').val();
                data.name = $('input[name=m_name]').val().trim();
                data.comment = $('input[name=m_comment]').val().trim();

                if (data.comment == '') {
                    data.comment = data.name;
                }

                data.type = $("input[name=actions]:checked").val();

                if (data.type === 'script') {
                    data.script_id = $('select[name=m_script]').val();
                } else if (data.type === 'easy') {
                    data.device_id = $('#easy_device').text().trim();
                    data.port = $('#easy_port').text().trim();
                    data.action = $('#easy_action').text().trim();
                }

                return data;
            }

            $('#apply_btn').click(function(){

                let data = getModalData();
                let message = validateMethod(data);

                if (message !== '') {
                    showModalError(message);
                    return false;
                }

                $.ajax({
                    url: store_url,
                    data: {'_token': _token, 'data': data},
                    success: function (resp) {
                        if (resp.result) {
                            if (data.id) {
                                editMethod(resp.data);
                            } else {
                                addMethod(resp.data);
                            }
                        }
                        cancel_btn.click();
                    }
                });
            });

            // edit method
            $('body').on('click', '.edit_btn', function() {
                let data = {};

                data.id = $(this).attr('data-id');
                data.type = $(this).attr('data-type');
                data.script_id = $(this).attr('data-script-id');
                data.device_id = $(this).attr('data-device');
                data.port = $(this).attr('data-port');
                data.action = $(this).attr('data-action');

                showEditModal(data);
            });

            // change easy/script/none in modal

            $('input[type=radio][name=actions]').change(function() {
                if (this.value === 'easy') {
                    $('#script_div').hide();
                    $('#easy_div').show();
                } else if (this.value === 'script') {
                    $('#easy_div').hide();
                    $('#script_div').show();
                } else {
                    $('#easy_div').hide();
                    $('#script_div').hide();
                }
                $('#error_div').hide();
            });

            // delete method

            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить метод «'+$(this).attr('data-name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                $('#del_cancel_btn').click();
                if (del_id) {
                    $.ajax({
                        url: del_url,
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#methods_div #div'+del_id).remove();
                                $('#events_div #ediv'+del_id).remove();
                            } else {
                                showErrorModal('Ошибка при удалении метода');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection

