@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление события', 'links' => [ route('events.index') => 'События']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('events.index') }}" class="btn btn-success m-b-10 m-l-5">Список событий</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'events.store', 'method' => 'post', 'id' => 'event_form',
                        'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                        {{ Form::bs_checkbox('active', 'Активность:', true, [], '&nbsp;&nbsp;Включить или выключить событие') }}

                        @if($can['events.create-system'])
                            {{ Form::bs_checkbox('is_system', 'Системное:', false, [], '&nbsp;&nbsp;Доступно для редактирования только администратору') }}
                        @else
                            <input type="hidden" name="is_system" value="0">
                        @endif
                        @if($can['events.create-hidden'])
                            {{ Form::bs_checkbox('is_hidden', 'Скрытое:', false, [], '&nbsp;&nbsp;Доступно для просмотра только администратору') }}
                        @else
                            <input type="hidden" name="is_hidden" value="0">
                        @endif

                        {{ Form::bs_hr() }}
                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" for="type"><strong></strong></label>
                            <div class="col-md-9">
                                <div class="btn-group-toggle" data-toggle="buttons">
                                    <label class="btn btn-success active">
                                        <input type="radio" name="type" autocomplete="off" checked value="method">  Выбор объекта и метода
                                    </label>
                                    <label class="btn btn-success">
                                        <input type="radio" name="type" autocomplete="off" value="script"> Выбор скрипта
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div id="method_div">
                            {{ Form::bs_autoselect('object', 'Объект:', $objects, null,  false, false) }}
                            {{ Form::bs_autoselect('method', 'Метод:', [], null,  false, false) }}

                            <div class="form-group row" id="method_params_div"
                                 @if(!old('method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 label-fix" for="method_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-6 label-fix" id="method_params_label" for="method_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="method_params" name="method_params"
                                                   type="text" value="{{ old('method_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="script_div" style="display: none;">
                            {{ Form::bs_autoselect('script', 'Скрипт:', $scripts, null,  false, false) }}
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
        const url_methods = '{{ route('ajax.objects.methods') }}';
        let methods = [];

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
            if (type === 'method') {
                let params = $("#event_form #method_params");
                if (params.is(":visible") && params.val().trim() === '') {
                    return 'Не указан параметр метода';
                }
                params_int = parseInt(params.val().trim());
                if (params.is(":visible") &&
                    (params.val().trim() != params_int || params_int < 0 || params_int > 100)) {
                    return 'Недопустимое значение параметра метода';
                }
            }
            return '';
        }

        $(document).ready(function () {
            $("#event_form #auto_sel_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#event_form #auto_sel_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#event_form #auto_sel_script").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#event_form #auto_sel_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('method_params');
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_method', data.methods, -1);
                        $('#auto_sel_method').trigger("chosen:updated");
                    }
                });
            });

            $('#event_form button[type=submit]').click(function(){
                let message = validateEvent();
                if (message !== '') {
                    $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
                    $('#init_btn').click();
                    return false;
                }
            });

            $('#event_form [name=type]').change(function(){
               if ($(this).val() === 'method') {
                   $('#script_div').hide();
                   $('#method_div').show();
               } else {
                   $('#method_div').hide();
                   $('#script_div').show();
               }
               return true;
            });

            // params

            function getMethodParams(methodId) {
                for (let i = 0; i < methods.length; i++) {
                    if (methods[i].id === methodId) {
                        return methods[i].params ? methods[i].params : '';
                    }
                }

                return '';
            }

            function hideParamsFields(id) {
                $('#event_form #'+id+'_div').hide();
                $('#event_form #'+id).val('');
            }

            function showParamsFields(id, params) {
                $('#event_form #'+id+'_label').text(params+'*:');
                $('#event_form #'+id).val('');
                $('#event_form #'+id+'_div').show();
            }

            $("#event_form #auto_sel_method").chosen().change(function() {
                const methodId = parseInt($(this).val());
                const params = getMethodParams(methodId);

                if (params === '') {
                    hideParamsFields('method_params');
                } else {
                    showParamsFields('method_params', params);
                }
            });
        });
    </script>
@endsection