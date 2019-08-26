@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Добавление отображения</h3> </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{ route('views.index') }}">Отображения</a></li>
                <li class="breadcrumb-item active">Добавление</li>
            </ol>
        </div>
    </div>
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
                    {!! Form::open(['route' => 'views.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            {{ Form::bs_title('Основные данные') }}

                            {{ Form::bs_radio('type_name', 'Тип элемента*:', $types, null, ['required' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_text('description', 'Описание:') }}

                            {{ Form::bs_title('Текст и графика') }}

                            {{ Form::bs_text('on_title_top','Надпись при включении:', null, [], 'Верхняя строка') }}
                            {{ Form::bs_text('on_title_bottom','', null, [], 'Нижняя строка') }}

                            {{ Form::bs_text('off_title_top','Надпись при выключении:', null, [], 'Верхняя строка') }}
                            {{ Form::bs_text('off_title_bottom','', null, [], 'Нижняя строка') }}

                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="off_title_top">
                                    Изображение при включении:
                                </label>
                                <div class="col-md-9">
                                    <p class="p-t-6">
                                        <img src="{{ asset(old('on_image',\App\Services\ImageService::NO_IMAGE_PATH)) }}"
                                             width="40" height="40" id="img_on" style="background: gray;">
                                        <button type="button" class="btn btn-default pull-right img_btn"
                                                data-toggle="modal" data-target="#img_modal" onclick="changeViewImage('on')"> Выбрать</button>
                                    </p>
                                </div>
                            </div>
                            {{ Form::bs_hidden('on_image', old('on_image','')) }}

                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="off_title_top">
                                    Изображение при выключении:
                                </label>
                                <div class="col-md-9">
                                    <p class="p-t-6">
                                        <img src="{{ asset(old('off_image',\App\Services\ImageService::NO_IMAGE_PATH)) }}"
                                             width="40" height="40" id="img_off" style="background: gray;">
                                        <button type="button" class="btn btn-default pull-right img_btn"
                                                data-toggle="modal" data-target="#img_modal" onclick="changeViewImage('off')"> Выбрать</button>
                                    </p>
                                </div>
                            </div>
                            {{ Form::bs_hidden('off_image', old('off_image','')) }}

                            {{ Form::bs_title('Расположение') }}

                            {{ Form::bs_select('room', 'Помещение*:', ["" => "Не указано"] + $rooms, null, ['required' => true]) }}
                            {{ Form::bs_select('scene', 'Сцена:', ["" => "Не указана"] + $scenes) }}
                            {{ Form::bs_number('position_left','Левый отступ (px):') }}
                            {{ Form::bs_number('position_top','Верхний отступ (px):') }}
                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
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
@endsection

@section('scripts')
    <script>
        let image_id;
        let url = '{{ asset('/') }}';

        function setViewImage(image) {
            $('#img_'+image_id).prop('src', url + image);
            $('input[name='+image_id+'_image]').val(image);
        }

        function changeViewImage(id) {
            image_id = id;
        }
    </script>
@endsection
