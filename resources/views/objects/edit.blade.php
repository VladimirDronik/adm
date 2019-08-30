@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование объекта № '. $object->id .' «'. $object->name .'»',
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
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($object, ['route' => ['objects.update', $object->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}
                        {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type', $object->type), ['required' => true]) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_autoselect('view', 'Отображение:', $views, old('view', $object->view),  false, false) }}

                        {{ Form::bs_submit_btn() }}

                        {{ Form::bs_title('Методы объекта') }}
                            <div class="form-group row">
                                <label class="col-md-3"><i>Название метода</i></label>
                                <div class="col-md-4"><i>Скрипт</i></div>
                                <div class="col-md-3"><i>Комментарий</i></div>
                                <div class="col-md-2 text-right"></div>
                            </div>
                            <div id="methods_div">
                                @foreach($object->methods as $method)
                                <div class="form-group row" id="div{{$method->id}}">
                                    <label class="col-md-3" id="name{{$method->id}}">
                                        {{$method->name}}
                                    </label>
                                    <div class="col-md-4" id="script{{$method->id}}">
                                        {{ optional($method->escript)->name }}
                                    </div>
                                    <div class="col-md-3" id="comment{{$method->id}}">
                                        {{ $method->comment }}
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" data-id="{{ $method->id }}"
                                                data-script-id="{{ $method->script }}" class="btn btn-info btn-sm btn-rounded edit_btn">
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
                    <input type="hidden" name="m_id" value="">
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="m_name">
                            <strong>Название*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input class="form-control" required name="m_name" type="text" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="m_script">
                            Скрипт:
                        </label>
                        <div class="col-md-9">
                            <select name="m_script" class="form-control">
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
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function () {
            let init_btn = $('#init_btn');
            let cancel_btn = $('#cancel_btn');
            let store_url = '{{ route('ajax.methods.store') }}';
            let del_url = '{{ route('ajax.methods.delete') }}';
            let object_id = '{{ $object->id }}';
            let del_id;

            $("#auto_sel_view").chosen({width:"100%", no_results_text: "Не найдено"});

            function showModalError(message) {
                $('#error_text').text(message);
                $('#error_div').show();
            }

            function showAddModal() {
                $('#m_id').val('');
                $('#method_modal_title').text('Добавление метода');
                $('#apply_btn').text('Добавить метод');
                $('#error_div').hide();
                init_btn.click();
            }

            function showEditModal(id) {
                $('#m_id').val(id);
                $('#method_modal_title').text('Редактирование метода');
                $('#apply_btn').text('Сохранить изменения');
                $('#error_div').hide();
                init_btn.click();
            }

            function addMethod(id, name, script_id, script_name, comment) {
                let html = `
                    <div class="form-group row" id="div${id}">
                         <label class="col-md-3" id="name${id}">${name}</label>
                         <div class="col-md-4" id="script${id}">${script_name}</div>
                         <div class="col-md-3" id="comment${id}">${comment}</div>
                         <div class="col-md-2 text-right">
                             <button type="button" data-id="${id}" data-script-id="${script_id}" class="btn btn-info btn-sm btn-rounded edit_btn">
                                                <i class="fa fa-cog fa-lg"></i></button>
                             <button type="button" data-id="${id}" data-name="${name}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                                <i class="fa fa-trash fa-lg"></i></button>
                         </div>
                    </div>`;

                $('#methods_div').append(html);
            }

            function editMethod(id, name, script_id, script_name, comment) {
                $('#name'+id).text(name);
                $('#script'+id).text(script_name);
                $('#comment'+id).text(comment);
            }

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(function(){

                let id = $('input[name=m_id]').val();
                let name = $('input[name=m_name]').val().trim();
                let script_id = $('select[name=m_script]').val();
                let comment = $('input[name=m_comment]').val().trim();

                if (name == '') {
                    showModalError('Не указано название метода');
                    return false;
                }

                if (comment == '') {
                    comment = name;
                }

                $.ajax({
                    url: store_url,
                    data: {'_token': _token, 'object_id': object_id, 'id': id, 'name': name,
                        'script_id': script_id, 'comment': comment},
                    success: function (data) {
                        if (data.result) {
                            if (id) {
                                editMethod(id, name, script_id, data.script_name, comment);
                            } else {
                                addMethod(data.id, name, script_id, data.script_name, comment);
                            }
                        }
                        cancel_btn.click();
                    }
                });
            });

            // edit method
            $('body').on('click', '.edit_btn', function() {
                let id = $(this).attr('data-id');
                let script_id = $(this).attr('data-script-id');

                $('input[name=m_id]').val(id);
                $('input[name=m_name]').val($('#name'+id).text().trim());
                $('select[name=m_script]').val(script_id);
                $('input[name=m_comment]').val($('#comment'+id).text().trim());

                showEditModal(id);
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
                                $('#div'+del_id).remove();
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

