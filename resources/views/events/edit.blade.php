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
                        {{ Form::bs_checkbox('is_system', 'Системное:') }}
                        {{ Form::bs_checkbox('is_hidden', 'Скрытое:') }}
                        {{ Form::bs_hr() }}
                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix" for="type"><strong></strong></label>
                            <div class="col-md-9">
                                <div class="btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-success @if($event->has_method) active @endif ">
                                        <input type="radio" name="type" autocomplete="off" @if($event->has_method) checked @endif  value="method">  Выбор объекта и метода
                                    </label>
                                    <label class="btn btn-success @if($event->has_script) active @endif">
                                        <input type="radio" name="type" autocomplete="off" @if($event->has_script) checked @endif value="script"> Выбор скрипта
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="method_div" @if($event->has_script) style="display: none;" @endif>
                            {{ Form::bs_autoselect('object', 'Объект:', $objects, old('object', $event->object),  false, false) }}
                            {{ Form::bs_autoselect('method', 'Метод:', $methods, old('method', $event->method),  false, false) }}
                        </div>
                        <div id="script_div" @if(!$event->has_script) style="display: none;" @endif>
                            {{ Form::bs_autoselect('script', 'Скрипт:', $scripts, old('script', $event->script),  false, false) }}
                        </div>

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
                                        {{$point->single_rus_type}}
                                    </label>
                                    <div class="col-md-7" id="description{{$point->id}}">
                                        {!! $point->description !!}
                                    </div>
                                    <div class="col-md-2 text-right">
                                        @if(!$point->is_close)
                                            <button type="button" data-id="{{ $point->id }}"
                                                    data-type="{{ $point->type }}" data-time="{{ $point->time }}" data-days="{{ $point->days }}" class="btn btn-info btn-sm btn-rounded edit_btn">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </button>
                                            <button type="button" data-id="{{ $point->id }}" data-type="{{ $point->type }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        @endif
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
        let is_valid = false;
        let year_dates = [];
        let m_year_date = $('#m_year_date');
        let del_id;

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
            let type = $('[name=type]:checked').val();
            if (type === 'script' && isEmptyAutoSelect('script')) {
                return 'Не указан скрипт';
            }
            if (type === 'method' && isEmptyAutoSelect('method')) {
                return 'Не указан метод';
            }
            if (type === 'method' && isEmptyAutoSelect('object')) {
                return 'Не указан объект';
            }
            return '';
        }

        $(document).ready(function () {

            $("#auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_script").chosen({width:"100%", no_results_text: "Не найдено"});

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

            $('[name=type]').change(function(){
                if ($(this).val() === 'method') {
                    $('#script_div').hide();
                    $('#method_div').show();
                } else {
                    $('#method_div').hide();
                    $('#script_div').show();
                }
                return true;
            });

            // points

            function showModalError(message) {
                $('#m_error_text').text(message);
                $('#m_error_div').show();
            }

            function clearModal() {
                $('#m_id').val('');
                $('#m_time').val('');
                $('#m_error_div').hide();
                $('#m_div_w').find('input:checkbox').prop('checked', false);
                $('#m_div_m').find('input:checkbox').prop('checked', false);
                $('#m_div_year_dates').html('');
                year_dates = [];
            }

            function showAddModal() {
                clearModal();
                $('#point_modal_title').text('Добавление периода');
                $('#apply_btn').text('Добавить период');
                init_btn.click();
            }

            function getModalData() {
                let data = {};
                data.type = $("input[name=m_type]:checked").val();
                data.id = $('#m_id').val();
                data.days = [];
                if (data.type === 'c') {
                    data.time = $('select[name=m_cron_period]').val();
                } else {
                    data.time = $('#m_time').val().trim();
                    if (data.type === 'w') {
                        $('input:checkbox[name=m_days]:checked').each(function() {
                            data.days.push($(this).val());
                        });
                    } else if (data.type === 'm') {
                        $('input:checkbox[name=m_dates]:checked').each(function() {
                            data.days.push($(this).val());
                        });
                    } else if (data.type === 'y') {
                        data.days = year_dates.slice();
                    }
                }
                return data;
            }

            function validatePoint(data) {
                if (data.type === 'c') {
                    if (data.time != parseInt(data.time)) {
                        return 'Недопустимый период';
                    }
                    return '';
                }
                if (data.time === '') {
                    return 'Не указано время';
                }
                if (!moment(data.time, "HH:mm", true).isValid()) {
                    return 'Недопустимое время';
                }
                if (data.type === 'w' && !data.days.length) {
                    return 'Не указаны дни недели';
                }
                if (data.type === 'm' && !data.days.length) {
                    return 'Не указаны даты месяца';
                }
                if (data.type === 'y' && !data.days.length) {
                    return 'Не указаны даты';
                }

                return '';
            }

            function hideModalDivs() {
                ['c','m','w','y','clock'].forEach(function(val) {
                   $('#m_div_'+val).hide();
                });
            }

            function initCronModal(data) {
                $('select[name=m_cron_period]').val(data.time);
            }

            function initDayModal(data) {
                let days = data.days.split(",");
                let m_days = $('input:checkbox[name=m_days]');

                days.forEach(function(day) {
                   m_days.filter('[value='+day+']').prop('checked',true);
                });
            }

            function initMonthModal(data) {
                let days = data.days.split(",");
                let m_days = $('input:checkbox[name=m_dates]');

                days.forEach(function(day) {
                    m_days.filter('[value='+day+']').prop('checked',true);
                });
            }

            function initYearModal(data) {
                let dates = data.days.split(",");
                let html = '';
                year_dates = [];
                dates.forEach(function(date) {
                    year_dates.push(date);
                    html += getYearDateButtonHtml(date);
                });
                $('#m_div_year_dates').html(html);
            }

            function showEditModal(data) {
                clearModal();
                hideModalDivs();

                $('#m_id').val(data.id);
                $('input:radio[name=m_type]').filter('[value='+data.type+']').prop('checked',true);
                $('#m_div_'+data.type).show();
                if (data.type !== 'c') {
                    $('#m_div_clock').show();
                    $('#m_time').val(data.time);
                }

                switch (data.type) {
                    case 'c': initCronModal(data); break;
                    case 'w': initDayModal(data); break;
                    case 'm': initMonthModal(data); break;
                    case 'y': initYearModal(data); break;
                    default: break;
                }

                $('#point_modal_title').text('Редактирование периода');
                $('#apply_btn').text('Сохранить изменения');

                init_btn.click();
            }

            function addPoint(data) {
                let html = `<div class="form-group row" id="div${data.id}">
                                <label class="col-md-3" id="type${data.id}">
                                    ${data.single_rus_type}
                                </label>
                                <div class="col-md-7" id="description${data.id}">
                                    ${data.description}
                                </div>
                                <div class="col-md-2 text-right">
                                <button type="button" data-id="${data.id}" data-type="${data.type}"
                                    data-time="${data.time}" data-days="${data.days}" class="btn btn-info btn-sm btn-rounded edit_btn">
                                       <i class="fa fa-cog fa-lg"></i>
                                </button>
                                <button type="button" data-id="${data.id}" data-type="${data.type}" class="btn btn-danger btn-rounded btn-sm del_btn">
                                    <i class="fa fa-trash fa-lg"></i>
                                </button>
                                </div>
                           </div>`;

                $('#points_div').append(html);
            }

            function editPoint(data) {
                $('#type'+data.id).text(data.single_rus_type);
                $('#description'+data.id).html(data.description);
            }

            $('#add_btn').click(showAddModal);

            $('#apply_btn').click(function(){

                let data = getModalData();
                let message = validatePoint(data);

                if (message !== '') {
                    showModalError(message);
                    return false;
                }

                data.event_id = event_id;

                $.ajax({
                    url: store_url,
                    data: {'_token': _token, 'data': data},
                    success: function (resp) {
                        if (resp.result) {
                            data.id ? editPoint(resp.data) : addPoint(resp.data);
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
                data.time = $(this).attr('data-time');
                data.days = $(this).attr('data-days');

                showEditModal(data);
            });

            // delete method

            $('body').on('click', '.del_btn', function() {
                del_id = $(this).attr('data-id');
                $('#del_modal_body').text('Удалить период?');
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
                    <button type="button" data-date="${date}" class="btn btn-outline-info btn-outline btn-addon m-b-10 m-l-5 year_date_btn">
                        ${date} <i class="ti-close"></i>
                    </button>
                `;
                return html;
            }

            function addYearDateToList(date) {
                if (date != '' && year_dates.indexOf(date) === -1) {
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

