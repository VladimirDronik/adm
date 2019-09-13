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
                    @if(!isset($main_network) || !isset($network) || !isset($vpn))
                        <p class="text-danger">Не удалось загрузить данные</p>
                    @else
                    {!! Form::model((object)[], ['route' => ['network.update'], 'method' => 'put', 'class' => 'form-horizontal form-bordered']) !!}
                    {{ csrf_field() }}
                    <div class="form-body">
                        {{ Form::bs_alert() }}

                        {{ Form::bs_title('Основной адрес сервера') }}

                        {{ Form::bs_text('main_ip', 'IP*:', old('main_ip',$main_network[0]), ['required' => true]) }}
                        {{ Form::bs_text('main_mask', 'Маска*:', old('main_mask',$main_network[1]), ['required' => true]) }}
                        {{ Form::bs_text('main_gateway', 'Шлюз*:', old('main_gateway',$main_network[2]), ['required' => true]) }}

                        {{ Form::bs_title('Адрес для подсети устройств') }}
                        {{ Form::bs_text('ip', 'IP*:', old('ip',$network[0]), ['required' => true]) }}
                        {{ Form::bs_text('mask', 'Маска*:', old('mask',$network[1]), ['required' => true]) }}

                        {{ Form::bs_title('Настройки VPN') }}
                        {{ Form::bs_text('vpn_address', 'Адрес сервера*:', old('vpn_address',$vpn[0]), ['required' => true]) }}
                        {{ Form::bs_text('vpn_login', 'Логин*:', old('vpn_login',$vpn[1]), ['required' => true]) }}
                        {{ Form::bs_text('vpn_password', 'Пароль*:', old('vpn_password',$vpn[2]), ['required' => true], 'Не менее 6 символов') }}
                    </div>
                    {{ Form::bs_submit_btn() }}
                    {!! Form::close() !!}
                    @endif
                    <div style="height: 150px;">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
@endsection
