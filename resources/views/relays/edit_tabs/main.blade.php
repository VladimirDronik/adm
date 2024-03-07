<br>
{{ Form::bs_simple_text('ID объекта:', $relay->id_object) }}

{{ Form::bs_simple_text('Тип подключения:', \App\Models\HomeObject::getGatewayNameByType($relay->gateway_type)) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if($relay->gateway_type == \App\Models\HomeObject::GATEWAY_HTTP)
    {{ Form::bs_autoselect('gateway_id', 'Контроллер*:', $devices, old('gateway_id', $relay->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}

    {{ Form::bs_autoselect('port_id', 'Порт*:', [], old('port_id'), false, false, ['required' => true], null, null, 3, false, true) }}
@else
    {{ Form::bs_autoselect('gateway_id', 'Устройство*:', $modbusSlavers, old('gateway_id', $relay->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}

    {{ Form::bs_autoselect('register_id', 'Регистр:', [], old('register_id'), false, false, [], null, 'Оставьте поле пустым, если не хотите менять регистр у методов', 3, false, false) }}
@endif

@include('messages.two')