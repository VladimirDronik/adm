@extends('layouts._layout')

@section('css')
    <link href="{{ asset('ela/css/lib/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Котел'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('engineering.index') }}" class="btn btn-success m-b-10 m-l-5"> <i class="fa fa-reply-all" aria-hidden="true"></i> Все устройства</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title"><h4>Добавление бойлера ГВС</h4></div>
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">

                    {!! Form::open(['route' => 'boiler_gvs.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered',
             'id' => 'engineering_form']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

                            {{ Form::bs_autoselect('type_boiler', 'Тип бойлера*:', $typesBoiler, old('type'),
                        false, false, ['required' => true], null) }}
                            {{ Form::bs_text('ip_address_boiler', 'ip адрес*:', null, ['required' => true]) }}

                    </div>

                    {{ Form::bs_submit_btn() }}

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
    @include('components.info_modal')

@endsection
