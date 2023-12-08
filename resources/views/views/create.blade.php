@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление отображения', 'links' => [ route('views.index') => 'Отображения']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('views.index') }}" class="btn btn-success m-b-10 m-l-5">Список отображений</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'views.store', 'method' => 'post', 'id' => 'view_form',
                            'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            {{ Form::bs_title('Основные данные') }}

                            {{ Form::bs_radio('type', 'Тип элемента*:', $types, null, ['required' => true]) }}
                            {{ Form::bs_text('description', 'Описание:') }}
                            {{ Form::bs_checkbox('active', 'Активность:', true) }}
                            {{ Form::bs_radio('safe_type', 'Защита от случайного нажатия:', $safeTypes, null) }}

                            <div id="id_object_div" style="display: block;">
                            {{ Form::bs_autoselect('id_object', 'Объект:', $objects, old('id_object'), false, false) }}
                            </div>

                            <div id="on_method_div" style="display: block;">
                            {{ Form::bs_autoselect('id_method', 'Метод вкл:', [], old('id_method'), false, false) }}
                            </div>

                            <div id="on_params_div" style="display: none;">
                                {{ Form::bs_autoselect('link', 'Ссылка:', $links, old('link'), false, false) }}
                            </div>

                            <div class="form-group row" id="on_method_params_div"
                                 @if(!old('id_method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 label-fix" for="on_method_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-6 label-fix" id="on_method_params_label" for="on_method_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="on_method_params" name="on_method_params"
                                                   type="text" value="{{ old('on_method_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="off_method_div" style="display: none;">
                                {{ Form::bs_autoselect('off_method', 'Метод выкл:', [], old('off_method'), false, false) }}
                            </div>

                            <div class="form-group row" id="off_method_params_div"
                                 @if(!old('off_method')) style="display: none;" @endif>
                                <label class="control-label text-right col-md-3 label-fix" for="off_method_params"></label>
                                <div class="col-md-9 pr-0">
                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-6 label-fix" id="on_method_params_label" for="off_method_params">...</label>
                                        <div class="col-md-6">
                                            <input class="form-control" autocomplete="off" id="off_method_params" name="off_method_params"
                                                   type="text" value="{{ old('off_method_params') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{ Form::bs_title('Текст и графика') }}

                            {{ Form::bs_text('title','Надпись:') }}



                                    <div class="row">
                                        <div class="col-md-2">
                                        </div>
                                        <div class="col-1">
                                            Цвет:
                                        </div>
                                        <div class="col-md-2" >
                                        <select name="color" style="background-color: #FFFFFF" onchange="this.style.backgroundColor = this.options[this.selectedIndex].style.backgroundColor;">
                                            <option style="background-color: #FFFFFF" value="null">Цвет соответствует цвету помещения</option>
                                            @foreach($colors AS $color)
                                                <option style="background-color: {{$color}}" value="{{$color}}">{{$color}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                    </div>




                            {{ Form::bs_image('icon','Изображение:', old('icon_image',\App\Services\ImageService::NO_IMAGE_PATH)) }}

                            {{ Form::bs_title('Расположение') }}

                            {{ Form::bs_select('room', 'Помещение*:', ["" => "Не указано"] + $rooms, null, ['required' => true]) }}
                            {{ Form::bs_select('scene', 'Сцена:', ["" => "Не указана"] + $scenes) }}
                            {{ Form::bs_number('position_left','Левый отступ (%):', old('position_left', 0), ['min' => 0, 'max' => 100, 'required' => false] ) }}
                            {{ Form::bs_number('position_top','Верхний отступ (%):', old('position_top', 0), ['min' => 0, 'max' => 100, 'required' => false] )  }}

                            <div id="additionallydiv" style="display: none;" >
                                <br>
                            {{ Form::bs_title('Дополнительно') }}
                                <div id="low_high_val_div" style="display: none;" >
                                    {{ Form::bs_radio('setting_from_app', 'Настройка из приложения:', ['true' => 'дa', 'false' => 'нет'], 'true') }}
                                    {{ Form::bs_number('lowval','Нижний порог шкалы:', old('lowval'), ['required' => false]) }}
                                    {{ Form::bs_number('highval','Верхний порог шкалы:', old('highval'), ['required' => false])  }}
                                </div>

                                <div id="labeldiv" style="display: none;" >
                                    {{ Form::bs_radio('pushlabel', 'Отображать нажатие:', ['true' => 'дa', 'false' => 'нет'], old('pushlabel', 'false')) }}
                                    {{ Form::bs_radio('modallabel', 'Показывать модальное окно:', ['true' => 'дa', 'false' => 'нет'], old('modallabel', 'true')) }}
                                    <div id="label_longclick_text_div">
                                        {{ Form::bs_text('label_longclick_text','Надпись при длительном нажатии:') }}
                                    </div>

                                    <div class="form-group row">
                                        <label class="control-label text-right col-md-3 label-fix">Связанный параметр:</label>
                                        <div class="col-md-11">
                                            {{ Form::bs_autoselect('related_parameter_object', 'Объект:', $relatedParameterObjects, old('related_parameter_object'), false, false) }}
                                            <div id='related_parameter_div' style="display: none">
                                                {{ Form::bs_autoselect('related_parameter', 'Параметр:', [], old('related_parameter'), false, false) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
        const url_objects = '{{ route('ajax.objects.getObjects') }}';
        const url_related_parameters = '{{ route('ajax.labels.related_parameters') }}';


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

        function createRelatedParameterSelect(target, options) {
            let sel = $(target);
            sel.html('');
            let s = '';
            for (let i = 0; i < options.length; i++) {
                s += '<option value="' + options[i].value + '">' + options[i].name + '</option>';
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

            // Принудительное назначение цвета кнопки для общего помещения
            // if ($("select[name=room]").val() == 0) {
            //     if (!$("select[name=color]").val() || $("select[name=color]").val() == 'null') {
            //         return 'Должен быть указан цвет элемента';
            //     }
            // }

            return '';
        }

        $(document).ready(function () {

            $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_off_method").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_link").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_related_parameter_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_related_parameter").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_related_parameter_object").chosen().change(function() {
                let object_id = $(this).val();
                if (object_id) {
                    $.ajax({
                        url: url_related_parameters,
                        data: {'_token': _token, 'object_id': object_id},
                        success: function (data) {
                            $('#related_parameter_div').show();
                            createRelatedParameterSelect('#auto_sel_related_parameter', data.related_parameters);
                            $('#auto_sel_related_parameter').trigger("chosen:updated");
                        }
                    });
                } else {
                    $('#related_parameter_div').hide();
                }
            });

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

            $("#view_form #auto_sel_id_method").chosen().change(function() {
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


            //Показываем или скрываем поле с текстом для label
            $('#view_form [name=modallabel]').change(function(){

                if ($(this).val() === 'true')
                  $('#label_longclick_text_div').show();
                else
                    $('#label_longclick_text_div').hide();
            });

            //

            $('#view_form [name=type]').change(function(){

                $('#additionallydiv').hide();
                $('#low_high_val_div').hide();
                $('#on_params_div').hide();
                $('#view_form #id_object_div').show();

                var type_obj = $(this).val();

                if ($(this).val() === 'switch') {
                    $('#view_form #off_method_div').show();
                } else if ($(this).val() === 'dimmer') {
                    $('#view_form #off_method_div').hide();
                    $('#view_form #on_method_div').hide();
                } else if ($(this).val() === 'conditioner') {
                    $('#view_form #off_method_div').hide();
                    $('#view_form #on_method_div').hide();
                } else if ($(this).val() === 'termostat') {
                    $('#view_form [name=lowval]').val(10);
                    $('#view_form [name=highval]').val(26);
                    $('#view_form [name=lowval]').attr('min', 0);
                    $('#view_form [name=highval]').attr('min', 0);
                    $('#view_form [name=lowval]').attr('max', 30);
                    $('#view_form [name=highval]').attr('max', 50);
                    $('#additionallydiv').show();
                    $('#low_high_val_div').show();
                } else if ($(this).val() === 'lightstat') {
                    $('#view_form [name=lowval]').val(0);
                    $('#view_form [name=highval]').val(30);
                    $('#view_form [name=lowval]').attr('min', 0);
                    $('#view_form [name=highval]').attr('min', 0);
                    $('#view_form [name=lowval]').attr('max', 100);
                    $('#view_form [name=highval]').attr('max', 100);
                    $('#additionallydiv').show();
                    $('#low_high_val_div').show();
                } else if ($(this).val() === 'carbsens') {
                    $('#view_form [name=lowval]').val(400);
                    $('#view_form [name=highval]').val(2000);
                    $('#view_form [name=lowval]').attr('min', 400);
                    $('#view_form [name=highval]').attr('min', 400);
                    $('#view_form [name=lowval]').attr('max', 2000);
                    $('#view_form [name=highval]').attr('max', 2000);
                    $('#additionallydiv').show();
                    $('#low_high_val_div').show();
                } else if ($(this).val() === 'link') {
                    $('#view_form #id_object_div').hide();
                    $('#view_form #on_method_div').hide();
                    $('#view_form #off_method_div').hide();
                    $('#on_params_div').show();
                } else if ($(this).val() === 'label') {
                    $('#additionallydiv').show();
                    $('#labeldiv').show();
                }
                else {
                    $('#view_form #on_method_div').show();
                    $('#view_form #off_method_div').hide();
                    $('#view_form #off_method_params_div').hide();

                }

                $.ajax({
                    url: url_objects,
                    data: {'_token': _token, 'type_object': type_obj},
                    success: function (data) {
                        objects = data.objects;
                        createMethodSelect('#auto_sel_id_object', data.objects, -1);
                        $('#auto_sel_id_object').trigger("chosen:updated");
                    }
                });

                return true;
            });

        });
    </script>
@endsection
