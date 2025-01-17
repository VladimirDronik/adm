@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', [
        'title' => 'Редактирование регулятора № '. $regulator->object_id,
        'links' => [ route('regulators.index') => 'Регуляторы'],
        'last_link' => 'Редактирование регулятора',
    ])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('regulators.index') }}" class="btn btn-success m-b-10 m-l-5">Список регуляторов</a>
                        <a href="{{ route('regulators.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить регулятор</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($regulator, ['route' => ['regulators.update', $regulator->id], 'id' => 'regulator_form', 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}

                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            <br>
                            {{ Form::bs_simple_text('ID объекта:', $regulator->object_id) }}

                            {{ Form::bs_text('name', 'Название*:', old('name', $regulator->object->name), ['required' => true]) }}

                            {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room', $regulator->room), false, false, [], null) }}

                            {{ Form::bs_simple_text('Источник данных:', $regulator->source) }}

                            {{ Form::bs_simple_text('Уставка:', $regulator->setpoint) }}

                            {{ Form::bs_text('min_setpoint', 'Минимальное значение уставки*:', old('min_setpoint', $regulator->min_setpoint), []) }}

                            {{ Form::bs_text('max_setpoint', 'Максимальное значение уставки*:', old('max_setpoint', $regulator->max_setpoint), []) }}
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
        $("#auto_sel_room").chosen({width:"100%", no_results_text: "Не найдено"});
    </script>
@endsection
