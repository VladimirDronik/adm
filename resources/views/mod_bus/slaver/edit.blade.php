@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Редактирование устройства № '. $slaver->id,
        'links' => [ route('mod_bus.slavers.index') => 'Устройства'],
        'last_link' => 'Редактирование устройства'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('mod_bus.slavers.index') }}" class="btn btn-success m-b-10 m-l-5">Устройства</a>
                        <a href="{{ route('mod_bus.slavers.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить устройство</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($slaver, ['route' => ['mod_bus.slavers.update', $slaver->id], 'id' => 'slaver_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $slaver->name), ['required' => true]) }}

                        {{ Form::bs_select('type', 'Тип*:', $types, old('type', $slaver->type), ['required' => true]) }}

                        {{ Form::bs_autoselect('bus', 'Шина*:', $buses, old('bus', $slaver->bus), false, false, ['required' => true], null, null, 3, false, true) }}

                        {{ Form::bs_number('address', 'Адрес*:', old('address', $slaver->address), ['min' => 1, 'max' => 247, 'required' => true]) }}
                    </div>

                    {{ Form::bs_submit_btn() }}

                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chosen/chosen.jquery.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#auto_sel_bus").chosen({width:"100%", no_results_text: "Не найдено"});
        });
    </script>
@endsection
