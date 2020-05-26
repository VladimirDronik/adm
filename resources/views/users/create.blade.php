@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Добавление пользователя', 'links' => [ route('users.index') => 'Пользователи']])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('users.index') }}" class="btn btn-success m-b-10 m-l-5">Список пользователей</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::open(['route' => 'users.store', 'method' => 'post', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', null, ['required' => true], 'Например: iphone Михаила') }}
                        {{ Form::bs_text('dev_id', 'ID пользователя*:', null, ['required' => true], 'Можно узнать при запуске приложения') }}
                        {{ Form::bs_text('telegram_id', 'ID Telegram:', null) }}
                        {{ Form::bs_textarea('push_id', 'ID для push:', null) }}
                        {{ Form::bs_number('phone_number', 'Телефон для SMS:', null, ['max' => 79999999999], 'в формате 7ХХХХХХХХХХ') }}

                        <br>
                        <br>
                        <h4>Приоритет уведомлений для каждого канала:</h4>

                        {{ Form::bs_radio('telegram_send', 'Для telegram:', $priority, old('telegram_send', -1), ['required' => true]) }}
                        {{ Form::bs_radio('push_send', 'Для push:', $priority, old('telegram_send', -1), ['required' => true]) }}
                        {{ Form::bs_radio('sms_send', 'Для SMS:', $priority, old('telegram_send', -1), ['required' => true]) }}

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
    <script>
    </script>
@endsection
