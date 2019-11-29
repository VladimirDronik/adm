@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
        ['title' => 'Добавление контроллера', 'links' => [ route('devices.index') => 'Контроллеры']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('devices.index') }}" class="btn btn-success m-b-10 m-l-5">Список контроллеров</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'devices.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                        {{ csrf_field() }}
                        <div class="form-body">
                            {{ Form::bs_alert() }}

                            {{ Form::bs_radio('type', 'Тип контроллера*:', $devtypes, old('type'), ['required' => true]) }}
                            {{ Form::bs_text('description', 'Название*:', null, ['required' => true]) }}
                            {{ Form::bs_text('ip_address', 'IP адрес*:', null, ['required' => true]) }}

                        </div>
                        {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
                <div style="height: 200px;">&nbsp;</div>
            </div>
        </div>
    </div>
@endsection
