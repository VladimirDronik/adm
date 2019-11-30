@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление счетчика', 'links' => [ route('counts.index') => 'Устройства: счетчики']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('counts.index') }}" class="btn btn-success m-b-10 m-l-5">Список счетчиков</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'counts.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_radio('type', 'Тип счетчика*:', $types, old('type', -1), ['required' => true]) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_autoselect('id_object', 'Объект*:', $objects, old('id_object'), false, false, ['required' => true]) }}
                        {{ Form::bs_number('impulse', 'Количество импульсов*:', old('impulse'), ['min' => 0, 'required' => true]) }}
                        {{ Form::bs_text('unit', 'Единица измерения*:', null, ['required' => true, 'maxlength' => 4], 'Например, m3 или kw/h') }}
                        {{ Form::bs_number('today_value', 'Значение за сегодня*:', old('today_value', 0), ['min' => 0, 'required' => true]) }}
                        {{ Form::bs_number('total_value', 'Общее значение*:', old('total_value', 0), ['min' => 0, 'required' => true]) }}
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
    <script src="{{ asset('ela/js/pagescripts/count.js') }}"></script>
    <script>
        $(document).ready(initCountForm);
    </script>
@endsection
