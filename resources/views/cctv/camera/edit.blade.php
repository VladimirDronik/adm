@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование камеры № '. $camera->id . ' «' . $camera->name .'»',
        'links' => [ route('cctv.index') => 'Видеонаблюдение'],
        'last_link' => 'Редактирование камеры'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('cctv.index') }}" class="btn btn-success m-b-10 m-l-5">Видеонаблюдение</a>
                        <button type="button" class="btn btn-success m-b-10 m-l-5" id="addDeviceBtn">Добавить устройство</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($camera, ['route' => ['cameras.update', $camera->id], 'id' => 'camera_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_simple_text('Производитель:', $camera->vendor_name) }}
                        {{ Form::bs_simple_text('Тип:', $camera->type_name) }}

                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" style="display: flex; align-items: center; justify-content: right;">
                                Изображение:
                            </label>
                            @if($camera->type == \App\Models\Camera::TYPE_DIRECT_LINK)
                                <a href="{{ $camera->link }}" target="_blank"><img src="{{ $camera->image }}" onerror="this.src='{{ asset('ela/images/no-cam-image.jpg') }}'" style="max-width: 126px; max-height: 80px;" loading="lazy"></img></a>
                                <div class="col-md-7">
                                    <p class="p-t-6">
                                        <input class="form-control" autocomplete="off" name="image" type="text" value="{{ $camera->image }}">
                                    </p>
                                </div>
                            @else
                                <a href="{{ route('cameras.get_stream', ['camera' => $camera->id]) }}" target="_blank"><img src="{{ $camera->image }}" onerror="this.src='{{ asset('ela/images/no-cam-image.jpg') }}'" style="max-width: 126px; max-height: 80px;" loading="lazy"></img></a>
                            @endif
                        </div>

                        @if($camera->vendor == 'ivideon')
                            {{ Form::bs_text('link', 'Ссылка*:', old('link', $camera->link), ['required' => true]) }}
                        @else
                            <div class="form-group row ">
                                <label class="control-label text-right col-md-3 label-fix" for="link">
                                    <strong>Ссылка RTSP*:</strong>
                                </label>
                                <div class="col-md-9">
                                    <input class="form-control" autocomplete="off" id="" required="" name="link" type="text" value="{{ old('link', $camera->link) }}">
                                    @if($camera->vendor == 'other')
                                        <small class="form-control-feedback">В ссылке на RTSP поток можно использовать данные уже добавленного видеорегистратора:<br>$login - имя пользователя видеорегистратора<br>$password - пароль видеорегистратора<br>$ip_address - ip адрес видеорегистратора<br><br>Например, rtsp://$login:$password@$ip_address/streaming/video0<br><br></small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{ Form::bs_checkbox('active', 'Активность*:', $camera->active) }}
                    </div>

                    {{ Form::bs_submit_btn() }}

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
    @include('cctv.create_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});

            $('#addDeviceBtn').click(function() {
                $('#modal_add_device_init_btn').click();
            });
        });
    </script>
@endsection
