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
                    {!! Form::model($view, ['route' => ['views.update', $view->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}
                        {{ Form::bs_title('Основные данные') }}

                        {{ Form::bs_radio('type', 'Тип элемента*:', $types, old('type', $view->type), ['required' => true]) }}
                        {{ Form::bs_text('description', 'Описание:') }}
                        {{ Form::bs_checkbox('active', 'Активность:') }}

                        {{ Form::bs_autoselect('id_object', 'Объект:', $objects, old('id_object', $view->id_object), false, false) }}
                        {{ Form::bs_autoselect('id_method', 'Метод объекта:', $methods, old('id_method', $view->id_method), false, false) }}

                        {{ Form::bs_title('Текст и графика') }}

                        {{ Form::bs_text('title','Надпись:') }}

                        {{ Form::bs_image('icon','Изображение:', old('icon_image', $view->icon_path)) }}

                        {{ Form::bs_title('Расположение') }}

                        {{ Form::bs_select('room', 'Помещение*:', ["" => "Не указано"] + $rooms,
                            is_null($view->room) ? 0 : $view->room, ['required' => true]) }}
                        {{ Form::bs_select('scene', 'Сцена:', ["" => "Не указана"] + $scenes) }}
                        {{ Form::bs_number('position_left','Левый отступ (px):') }}
                        {{ Form::bs_number('position_top','Верхний отступ (px):') }}

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
        let url_methods = '{{ route('ajax.objects.methods') }}';

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
            return '';
        }

        $(document).ready(function () {

            $("#auto_sel_id_object").chosen({width:"100%", no_results_text: "Не найдено"});
            $("#auto_sel_id_method").chosen({width:"100%", no_results_text: "Не найдено"});

            $("#auto_sel_id_object").chosen().change(function() {
                let object_id = $(this).val();

                $.ajax({
                    url: url_methods,
                    data: {'_token': _token, 'object_id': object_id},
                    success: function (data) {
                        createMethodSelect('#auto_sel_id_method', data.methods, -1);
                        $('#auto_sel_id_method').trigger("chosen:updated");
                    }
                });
            });

            $('button[type=submit]').click(function(){
                let message = validateView();
                if (message !== '') {
                    $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
                    $('#init_btn').click();
                    return false;
                }
            });
        });
    </script>
@endsection
