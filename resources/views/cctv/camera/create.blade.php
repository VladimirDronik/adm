@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление камеры', 'links' => [ route('cctv.index') => 'Видеонаблюдение']])
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
                    {!! Form::open(['route' => 'cameras.store', 'method' => 'post', 'id' => 'camera_form', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name'), ['required' => true]) }}

                        {{ Form::bs_text('link', 'Ссылка*:', old('link'), ['required' => true]) }}

                        {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room'), false, false, ['required' => true], null) }}

                        {{ Form::bs_checkbox('active', 'Активность*:', true) }}

                        <input name="type" value="ivideon" hidden>
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
            $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
        });
    </script>
@endsection
