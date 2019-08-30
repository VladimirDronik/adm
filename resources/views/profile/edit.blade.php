@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Профиль'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($user, ['route' => ['profile.update'], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_title('Основные данные') }}

                        {{ Form::bs_text('login', 'Логин*:', null, ['required' => true]) }}

                        {{ Form::bs_title('Смена пароля') }}

                        {{ Form::bs_password('password', 'Новый пароль:', [], 'Не менее 6 символов') }}
                        {{ Form::bs_password('password_confirmation', 'Повтор пароля:') }}

                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
