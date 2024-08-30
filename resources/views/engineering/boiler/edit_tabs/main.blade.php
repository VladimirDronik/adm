{{ Form::bs_simple_text('ID объекта:', $boiler->id_object) }}
{{ Form::bs_simple_text('Протокол обмена:', $boiler->protocol) }}
{{ Form::bs_simple_text('Тип подключения:', $boiler->gateway_type) }}
{{ Form::bs_simple_text('Тип котла:', $boiler->rus_type) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if($boiler->gateway_type == \App\Models\HomeObject::GATEWAY_HTTP)
    {{ Form::bs_autoselect('gateway_id', 'Контроллер*:', $devices, old('gateway_id', $boiler->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}
@else
    {{ Form::bs_autoselect('gateway_id', 'Устройство*:', $modbusSlavers, old('gateway_id', $boiler->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}
@endif

{{ Form::bs_autoselect('outdoor_sensor', 'Уличный датчик температуры:', $termostats, old('outdoor_sensor', $boiler->outdoor_sensor), false, false, [], null) }}
