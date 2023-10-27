@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование видеорегистратора № '. $recorder->id . ' «' . $recorder->name .'»',
        'links' => [ route('cctv.index') => 'Видеонаблюдение'],
        'last_link' => 'Редактирование видеорегистратора'])
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
                    {!! Form::model($recorder, ['route' => ['recorders.update', $recorder->id], 'id' => 'recorder_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $recorder->name), ['required' => true]) }}

                        {{ Form::bs_simple_text('Производитель:', $recorder->vendor) }}

                        {{ Form::bs_text('ip_address', 'IP адрес*:', old('ip_address', $recorder->ip_address), ['required' => true]) }}

                        {{ Form::bs_text('login', 'Логин*:', old('login', $recorder->login), ['required' => true]) }}

                        {{ Form::bs_text('new_password', 'Пароль*:', null, [], 'Оставьте поле пустым, если хотите оставить старый пароль') }}
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
