@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование бойлера ГВС № '. $boiler->id_object . ' «' . $boiler->name .'»',
        'links' => [ route('engineering.index') => 'инженерное оборудование'],
        'last_link' => 'Редактирование '])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('engineering.index') }}" class="btn btn-success m-b-10 m-l-5">Cписок устройств</a>
                        <a href="{{ route('boiler.edit', $boiler->id_object) }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($boiler, ['route' => ['boiler_gvs.update', $boiler->id_object], 'id' => 'boiler_form',
                        'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_simple_text('ID объекта:', $boiler->id_object) }}
                        {{ Form::bs_simple_text('Тип:', $boiler->model) }}
                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
                        {{ Form::bs_text('ip_address', 'ip адрес*:', null, ['required' => true]) }}

                        <div style="height: 10px;">&nbsp;</div>
                        <hr>
                        <div style="height: 40px;">&nbsp;</div>


                        {{ Form::bs_simple_text('Cостояние:', $boiler->state ? $boiler->state : 'N/A') }}
                        {{ Form::bs_simple_text('Режим:', $boiler->mode ? $boiler->mode : 'N/A') }}
                        {{ Form::bs_simple_text('Давление:', $boiler->pressue ? $boiler->pressue : 'N/A' ) }}


                    </div>


                    {{ Form::bs_submit_btn() }}


                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
                <button type="button" id="init_btn" style="display: none;" data-toggle="modal" data-target="#info_modal">&nbsp;</button>
                <button type="button" id="init_method_btn" style="display: none;" data-toggle="modal" data-target="#method_modal">&nbsp;</button>
                <button type="button" id="init_message_btn" style="display: none;" data-toggle="modal" data-target="#message_modal">

            </div>
        </div>
    </div>
{{--
    @include('objects.message_modal')
    @include('objects.method_modal')

    @include('components.info_modal')
    @include('components.del_modal')
    @include('components.create_object_modal', compact('object_types'))
--}}
@endsection

@section('scripts')


@endsection
