@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление камеры', 'links' => [ route('cameras.index') => 'Камеры']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('cameras.index') }}" class="btn btn-success m-b-10 m-l-5">Камеры</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'cameras.store', 'method' => 'post', 'id' => 'camera_form', 'class' => 'form-horizontal form-bordered', 'files' => true]) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        <div class="form-group row ">
                            <label class="control-label text-right col-md-3 label-fix">
                                <strong>Изображение*:</strong>
                            </label>
                            <div class="col-md-9">
                                <p class="p-t-6">
                                    <img id="image-preview" src="#" style="max-width: 100px; max-height: 100px; display: none;">
                                    <input type="file" id="image-upload" name="image" accept="image/*" required>
                                </p>
                            </div>
                        </div>

                        {{ Form::bs_text('link', 'Ссылка*:', old('link'), ['required' => true]) }}

                        {{ Form::bs_autoselect('room_id', 'Помещение*:', $rooms, old('room_id'), false, false, ['required' => true], null) }}

                        {{ Form::bs_text('sort', 'Сортирвока*:', old('sort'), ['required' => true]) }}

                        {{ Form::bs_checkbox('active', 'Активность*:', true) }}

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
        $(document).ready(function () {
            $("#auto_sel_room_id").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#image-upload').change(function(event) {
                var file = event.target.files[0];
                if (file) {
                    var reader = new FileReader();

                    reader.onload = function() {
                        $('#image-preview').attr('src', reader.result);
                        $('#image-preview').show();
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
