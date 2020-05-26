@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Редактирование пользователя', 'links' => [ route('users.index') => 'Пользователи']])
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
                    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_text('name', 'Название*:', old('name', $user->name), ['required' => true], 'Например: iphone Михаила') }}
                        {{ Form::bs_text('dev_id', 'ID пользователя*:', old('dev_id', $user->dev_id), ['required' => true], 'Можно узнать при запуске приложения') }}
                        {{ Form::bs_text('telegram_id', 'ID Telegram:', old('telegram_id', $user->telegram_id)) }}
                        {{ Form::bs_textarea('push_id', 'ID для push:', old('push_id', $user->push_id)) }}
                        {{ Form::bs_number('phone_number', 'Телефон для SMS:', null, ['max' => 79999999999], 'в формате 7ХХХХХХХХХХ') }}

                        <br>
                        <br>
                        <h4>Приоритет уведомлений для каждого канала:</h4>

                        {{ Form::bs_radio('telegram_send', 'Для telegram:', $priority, old('telegram_send', $user->telegram_send), ['required' => true]) }}
                        {{ Form::bs_radio('push_send', 'Для push:', $priority, old('push_send', $user->push_send), ['required' => true]) }}
                        {{ Form::bs_radio('sms_send', 'Для SMS:', $priority, old('sms_send', $user->sms_send), ['required' => true]) }}

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
