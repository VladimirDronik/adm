@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление видеорегистратора', 'links' => [ route('cctv.index') => 'Видеонаблюдение']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('cctv.index') }}" class="btn btn-success m-b-10 m-l-5">Видеонаблюдение</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'recorders.store', 'method' => 'post', 'id' => 'recorder_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_radio('vendor', 'Производитель*:', $vendors, old('vendor'), ['required' => true]) }}

                        {{ Form::bs_text('ip_address', 'IP адрес*:', old('ip_address'), ['required' => true]) }}

                        {{ Form::bs_text('login', 'Логин*:', old('login'), ['required' => true]) }}

                        {{ Form::bs_text('password', 'Пароль*:', old('password'), ['required' => true]) }}

                        {{ Form::bs_text('number_of_cameras', 'Количество камер*:', old('number_of_cameras'), ['required' => true]) }}
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
        });
    </script>
@endsection
