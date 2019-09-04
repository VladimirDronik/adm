@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('ela/css/lib/clockpicker/clockpicker.css') }}" rel="stylesheet">
    <link href="{{ asset('ela/css/lib/datepicker/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование события «'. $event->name .'»',
        'links' => [ route('events.index') => 'События'],
        'last_link' => 'Редактирование события'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('events.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок событий</a>
                        <a href="{{ route('events.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить событие</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($event, ['route' => ['events.update', $event->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_autoselect('object', 'Объект*:', $objects, old('object', $event->object),  false, false, ['required' => true]) }}
                        {{ Form::bs_autoselect('method', 'Метод*:', $methods, old('method', $event->method),  false, false, ['required' => true]) }}

                        {{ Form::bs_submit_btn() }}

                        {{ Form::bs_title('Расписание события') }}
                        <div class="form-group row">
                            <label class="col-md-3"><i>Тип периода</i></label>
                            <div class="col-md-7"><i>Описание</i></div>
                            <div class="col-md-2 text-right"></div>
                        </div>
                        <div id="points_div">
                            @foreach($event->points as $point)
                                <div class="form-group row" id="div{{$point->id}}">
                                    <label class="col-md-3" id="type{{$point->id}}">
                                        {{$point->rus_type}}
                                    </label>
                                    <div class="col-md-7" id="description{{$point->id}}">
                                        {{ $point->description }}
                                    </div>
                                    <div class="col-md-2 text-right">
                                        <button type="button" data-id="{{ $point->id }}"
                                                data-type="{{ $point->type }}" class="btn btn-info btn-sm btn-rounded edit_btn">
                                            <i class="fa fa-cog fa-lg"></i>
                                        </button>
                                        <button type="button" data-id="{{ $point->id }}" data-type="{{ $point->type }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 text-left">
                                <button id="add_btn" type="button" class="btn btn-primary">
                                    <i class="fa fa-plus fa-lg"></i> Добавить период
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>

                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#point_modal">&nbsp;</button>
                <button type="button" id="init_info_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>

            </div>
        </div>
    </div>

    @include('events.point_modal')
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script src="{{ asset('ela/js/lib/clockpicker/clockpicker.js') }}"></script>
    <script src="{{ asset('ela/js/moment.js') }}"></script>
    <script src="{{ asset('ela/js/lib/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('ela/js/lib/datepicker/datepicker-ru.min.js') }}" charset="UTF-8"></script>
    <script>
        let init_btn = $('#init_btn');
        let cancel_btn = $('#cancel_btn');
        let store_url = '{{ route('ajax.points.store') }}';
        let del_url = '{{ route('ajax.points.delete') }}';
        let url_methods = '{{ route('ajax.objects.methods') }}';
        let url_name = '{{ route('ajax.events.validation.name') }}';
        let event_id = '{{ $event->id }}';
        let del_id;
        let is_valid = false;
        let year_dates = [];
        let m_year_date = $('#m_year_date');

        function createMethodSelect(target, options, selected) {
            let sel = $(target);
            sel.html('');
            let s = '<option value="">Не выбрано</option>';
            for (let i = 0; i < options.length; i++) {
                if (selected == options[i].id)
                    s += '<option selected value="' + options[i].id + '">' + options[i].name + '</option>';
                else
                    s += '<option value="' + options[i].id + '">' + options[i].name + '</option>';
            }
            sel.append(s);
        }

        function isEmptyInput(name) {
            return $('input[name='+name+']').val().trim() == '';
        }

        function isEmptyAutoSelect(name) {
            return $('#auto_sel_'+name).val().trim() == '';
        }

        function validateEvent() {
            if (isEmptyInput('name')) {
                return 'Не указано название события';
            }
            if (isEmptyAutoSelect('object')) {
                return 'Не указан объект';
            }
            if (isEmptyAutoSelect('method')) {
                return 'Не указан метод';
            }
            return '';
        }

        $(document).ready(function () {

            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#clockpicker').clockpicker({donetext: 'Применить'});
            $('#m_year_date').datepicker({format: "dd.mm", language: "ru", autoclose: true});

            // event

            $("#auto_sel_object").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        createMethodSelect('#auto_sel_method', data.methods, -1);
                        $('#auto_sel_method').trigger("chosen:updated");
                    }
                });
            });

            $('button[type=submit]').click(function(){
                if (!is_valid) {
                    let message = validateEvent();
                    if (message !== '') {
                        $('#info_modal_body').html('<span class="text-danger">' + message + '</span>');
                        $('#init_info_btn').click();
                        return false;
                    }

                    let name = $('input[name=name]').val().trim();

                    $.ajax({
                        url: url_name,
                        data: {'_token': _token, 'id': event_id, 'name': name},
                        success: function (data) {
                            if (data.result) {
                                is_valid = true;
                                $('button[type=submit]').click();
                            } else {
                                $('#info_modal_body').html('<span class="text-danger">' + data.message + '</span>');
                                $('#init_info_btn').click();
                            }
                        }
                    });

                    return false;
                }
                return true;
            });

            // points

            function showModalError(message) {
                $('#m_error_text').text(message);
                $('#m_error_div').show();
            }

            function showAddModal() {
                $('#m_id').val('');
                $('#point_modal_title').text('Добавление периода');
                $('#apply_btn').text('Добавить период');
                $('#m_error_div').hide();
                $('#m_div_w').find('input:checkbox').prop('checked', false);
                $('#m_div_m').find('input:checkbox').prop('checked', false);
                $('#m_div_year_dates').html('');
                year_dates = [];
                init_btn.click();
            }

            function getModalData(type) {
                let data = { 'type': type };
                data.id = $('#m_id').val();
                if (type === 'c') {
                    data.time = $('select[name=m_cron_period]').val();
                } else {
                    data.time = $('#m_time').val().trim();
                    data.days = [];
                    if (type === 'w') {
                        $('input:checkbox[name=m_days]:checked').each(function() {
                            data.days.push($(this).val());
                        });
                    } else if (type === 'm') {
                        $('input:checkbox[name=m_dates]:checked').each(function() {
                            data.days.push($(this).val());
                        });
                    } else if (type === 'y') {
                        data.days = year_dates.slice();
                    }
                }
                return data;
            }

            function validatePoint(data) {
                return '123';
            }

            function showEditModal(id) {
                $('#m_id').val(id);
                $('#point_modal_title').text('Редактирование периода');
                $('#apply_btn').text('Сохранить изменения');
                $('#error_div').hide();
                init_btn.click();
            }

            function addPoint(data) {
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

                let type = $("input[name='m_type']:checked").val();
                let data = getModalData(type);

                let message = validatePoint(data);

                if (message !== '') {
                    showModalError(message);
                    return false;
                }

                $.ajax({
                    url: store_url,
                    data: {'_token': _token, 'event_id': event_id, 'data': data},
                    success: function (data) {
                        if (data.result) {
                            if (id) {
                                editPoint(id, data);
                            } else {
                                addPoint(data);
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
                                showErrorModal('Ошибка при удалении');
                            }
                        }
                    });
                }
            });

            // change modal type

            function refreshModalDivs(type) {
                if (type === 'c') {
                    $('#m_div_clock').hide();
                } else {
                    $('#m_div_clock').show();
                }

                ['c','m','w','y'].forEach(function(value) {
                    if (value !== type) {
                        $('#m_div_'+value).hide();
                    }
                });

                $('#m_div_'+type).show();
            }

            function getYearDateButtonHtml(date) {
                let html = `
                    <button type="button" data-date="${date}" class="btn btn-outline-success btn-outline btn-addon m-b-10 m-l-5 year_date_btn">
                        ${date} <i class="ti-close"></i>
                    </button>
                `;
                return html;
            }

            function addYearDateToList(date) {
                if (year_dates.indexOf(date) === -1) {
                    year_dates.push(date);
                    $('#m_div_year_dates').append(getYearDateButtonHtml(date));
                }
            }

            $('input[type=radio][name=m_type]').change(function() {
                refreshModalDivs(this.value);
            });

            $('#add_year_date_btn').click(function() {
                let year_date = m_year_date.val().trim();
                m_year_date.val('');
                addYearDateToList(year_date);
            });

            $('body').on('click', '.year_date_btn', function() {
                let date = $(this).attr('data-date');
                for (let i = year_dates.length - 1; i >= 0; i--) {
                    if (year_dates[i] == date) {
                        year_dates.splice(i, 1);
                        break;
                    }
                }
                $(this).remove();
            });
        });
    </script>
@endsection

