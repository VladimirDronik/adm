@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/colorpicker/bootstrap-colorpicker.min.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование сцены № '. $scene->id .' «'. $scene->label .'»',
        'links' => [ route('scenes.index') => 'Сцены'],
        'last_link' => 'Редактирование сцены'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('scenes.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок сцен</a>
                        <a href="{{ route('scenes.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить сцену</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($scene, ['route' => ['scenes.update', $scene->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('label', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_checkbox('active', 'Активность:') }}

                        @if(count($images))
                            {{ Form::bs_image('','Изображение:',old('_image', $scene->image_path)) }}
                        @else
                            {{ Form::bs_simple_text('Изображение:','Не найдены варианты для выбора') }}
                            {{ Form::bs_hidden('_image','') }}
                        @endif

                        {{ Form::bs_color('background_color', 'Цвет фона:') }}

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
    <script src="{{ asset('ela/js/lib/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
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

        $(document).ready(function () {
            $('input[name=background_color]').colorpicker();
        });
    </script>
@endsection
