@extends('layouts._layout')

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
                    {!! Form::open(['route' => 'views.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}
                            {{ Form::bs_title('Основные данные') }}

                            {{ Form::bs_radio('type_name', 'Тип элемента*:', $types, null, ['required' => true]) }}
                            {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_text('description', 'Описание:') }}
                            {{ Form::bs_checkbox('active', 'Активность:', true) }}

                            {{ Form::bs_title('Текст и графика') }}

                            {{ Form::bs_text('on_title_top','Надпись при включении:', null, [], 'Верхняя строка') }}
                            {{ Form::bs_text('on_title_bottom','', null, [], 'Нижняя строка') }}

                            {{ Form::bs_text('off_title_top','Надпись при выключении:', null, [], 'Верхняя строка') }}
                            {{ Form::bs_text('off_title_bottom','', null, [], 'Нижняя строка') }}

                            {{ Form::bs_image('on','Изображение при включении:',old('on_image',\App\Services\ImageService::NO_IMAGE_PATH)) }}
                            {{ Form::bs_image('off','Изображение при выключении:',old('off_image',\App\Services\ImageService::NO_IMAGE_PATH)) }}

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
