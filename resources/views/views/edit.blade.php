@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование отображения',
        'links' => [ route('views.index') => 'Отображения'],
        'last_link' => 'Редактирование отображения'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок отображений</a>
                        <a href="{{ route('views.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить отображение</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($view, ['route' => ['views.update', $view->id], 'method' => 'put', 'id' => 'view_form',
                            'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}

                        {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type', $view->type), ['required' => true]) }}
                        {{ Form::bs_text('description', 'Описание:') }}
                        {{ Form::bs_checkbox('active', 'Активность:') }}

                        {{ Form::bs_autoselect('id_object', 'Объект:', $objects, old('id_object', $view->id_object), false, false) }}

                        <div id="on_method_div" @if($view->is_dimmer) style="display: none;" @endif>
                        {{ Form::bs_autoselect('id_method', 'Метод вкл:', $methods, old('id_method', $view->on_method), false, false) }}
                        </div>

                        <div class="form-group row" id="on_method_params_div"
                             @if(is_null($view->on_method_params) && !old('id_method')) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="on_method_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row ">
                                    <label class="control-label text-right col-md-6 label-fix" id="on_method_params_label" for="on_method_params">
                                        {{ optional($view->emethod)->params }}*:</label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="on_method_params" name="on_method_params"
                                               type="text" value="{{ old('on_method_params', $view->on_method_params) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="off_method_div" @if(!$view->is_switch && !old('off_method')) style="display: none;" @endif>
                            {{ Form::bs_autoselect('off_method', 'Метод выкл:', $methods, old('off_method', $view->off_method), false, false) }}
                        </div>

                        <div class="form-group row" id="off_method_params_div"
                             @if(!$view->is_switch || (is_null($view->off_method_params) && !old('off_method'))) style="display: none;" @endif>
                            <label class="control-label text-right col-md-3 label-fix" for="off_method_params"></label>
                            <div class="col-md-9 pr-0">
                                <div class="form-group row">
                                    <label class="control-label text-right col-md-6 label-fix" id="on_method_params_label" for="off_method_params">
                                        {{ optional($view->offmethod)->params }}*:
                                    </label>
                                    <div class="col-md-6">
                                        <input class="form-control" autocomplete="off" id="off_method_params" name="off_method_params"
                                               type="text" value="{{ old('off_method_params', $view->off_method_params) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{ Form::bs_title('Текст и графика') }}

                        {{ Form::bs_text('title','Надпись:') }}

                        {{ Form::bs_image('icon','Изображение:', old('icon_image', $view->icon_path)) }}

                        {{ Form::bs_title('Расположение') }}

                        {{ Form::bs_select('room', 'Помещение*:', ["" => "Не указано"] + $rooms,
                            is_null($view->room) ? 0 : $view->room, ['required' => true]) }}
                        {{ Form::bs_select('scene', 'Сцена:', ["" => "Не указана"] + $scenes) }}
                        {{ Form::bs_number('position_left','Левый отступ (%):', old('position_left', $view->position_left), ['min' => 0, 'max' => 100, 'required' => false] ) }}
                        {{ Form::bs_number('position_top','Верхний отступ (%):', old('position_top', $view->position_right), ['min' => 0, 'max' => 100, 'required' => false] ) }}

                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
            </div>
        </div>
    </div>
    <div class="modal" id="img_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Выберите изображение</h4>
                </div>
                <div class="modal-body" style="background: gray;">
                    @foreach($images as $image)
                        <img src="{{ asset($image) }}" width="40" height="40" style="cursor: pointer;"
                             onclick="setViewImage('{{$image}}');" data-dismiss="modal">&nbsp;&nbsp;&nbsp;
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        let image_id;
        let url = '{{ asset('/') }}';
        const url_methods = '{{ route('ajax.objects.methods') }}';
        let methods = [];

        function setViewImage(image) {
            $('#img_'+image_id).prop('src', url + image);
            $('input[name='+image_id+'_image]').val(image);
        }

        function changeViewImage(id) {
            image_id = id;
        }

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

        function validateView() {

            if (!$("input[name=type]:checked").val()) {
                return 'Не указан тип элемента';
            }
            if (!$("select[name=room]").val()) {
                return 'Не указано помещение';
            }

            let params = $("#view_form #on_method_params");
            if (params.is(":visible") && params.val().trim() === '') {
                return 'Не указан параметр метода';
            }
            params_int = parseInt(params.val().trim());
            if (params.is(":visible") &&
                (params.val().trim() != params_int || params_int < 0 || params_int > 100)) {
                return 'Недопустимое значение параметра метода';
            }

            params = $("#view_form #off_method_params");
            if (params.is(":visible") && params.val().trim() === '') {
                return 'Не указан параметр метода';
            }
            params_int = parseInt(params.val().trim());
            if (params.is(":visible") &&
                (params.val().trim() != params_int || params_int < 0 || params_int > 100)) {
                return 'Недопустимое значение параметра метода';
            }

            return '';
        }

        function initMethodsVar(object_id) {
            if (object_id) {
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                    }
                });
            }
        }

        $(document).ready(function () {
            initMethodsVar({{ optional($view->eobject)->id }});

            $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_off_method").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_id_object").chosen().change(function() {
                let object_id = $(this).val();
                hideParamsFields('on_method_params');
                hideParamsFields('off_method_params');
                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        methods = data.methods;
                        createMethodSelect('#auto_sel_id_method', data.methods, -1);
                        $('#auto_sel_id_method').trigger("chosen:updated");
                        createMethodSelect('#auto_sel_off_method', data.methods, -1);
                        $('#auto_sel_off_method').trigger("chosen:updated");
                    }
                });
            });

            $('#view_form button[type=submit]').click(function(){
                let message = validateView();
                if (message !== '') {
                    $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
                    $('#init_btn').click();
                    return false;
                }
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
                $('#view_form #'+id+'_div').hide();
                $('#view_form #'+id).val('');
            }

            function showParamsFields(id, params) {
                $('#view_form #'+id+'_label').text(params+'*:');
                $('#view_form #'+id).val('');
                $('#view_form #'+id+'_div').show();
            }

            $("#auto_sel_id_method").chosen().change(function() {
                const methodId = parseInt($(this).val());
                const params = getMethodParams(methodId);

                if (params === '') {
                    hideParamsFields('on_method_params');
                } else {
                    showParamsFields('on_method_params', params);
                }
            });

            $("#view_form #auto_sel_off_method").chosen().change(function() {
                const methodId = parseInt($(this).val());
                const params = getMethodParams(methodId);

                if (params === '') {
                    hideParamsFields('off_method_params');
                } else {
                    showParamsFields('off_method_params', params);
                }
            });

            //

            $('#view_form [name=type]').change(function(){
                if ($(this).val() === 'switch') {
                    $('#view_form #off_method_div').show();
                } else if ($(this).val() === 'dimmer'){
                    $('#view_form #off_method_div').hide();
                    $('#view_form #on_method_div').hide();
                } else {
                    $('#view_form #on_method_div').show();
                    $('#view_form #off_method_div').hide();
                    $('#view_form #off_method_params_div').hide();
                }
                return true;
            });
        });
    </script>
@endsection
