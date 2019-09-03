@extends('layouts._layout')

@section('breadcrumbs')
    @includeIf('components.breadcrumbs',
       ['title' => 'Сеть и VPN'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    {!! Form::model($network, ['route' => ['network.update'], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_title('Основной адрес сервера') }}

                        {{ Form::bs_text('main_ip', 'IP*:', null, ['required' => true]) }}
                        {{ Form::bs_text('main_mask', 'Маска*:', null, ['required' => true]) }}
                        {{ Form::bs_text('main_gateway', 'Шлюз*:', null, ['required' => true]) }}

                        {{ Form::bs_title('Адрес для подсети устройств') }}
                        {{ Form::bs_text('ip', 'IP*:', null, ['required' => true]) }}
                        {{ Form::bs_text('mask', 'Маска*:') }}

                        {{ Form::bs_title('Настройки VPN') }}
                        {{ Form::bs_text('vpn_address', 'Адрес сервера*:', null, ['required' => true]) }}
                        {{ Form::bs_text('vpn_login', 'Логин*:', null, ['required' => true]) }}
                        {{ Form::bs_password('vpn_password', 'Пароль:') }}

                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                    <div style="height: 150px;">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
@endsection
