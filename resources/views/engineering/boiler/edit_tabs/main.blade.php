{{ Form::bs_simple_text('ID объекта:', $boiler->id_object) }}
{{ Form::bs_simple_text('Протокол обмена:', $boiler->protocol) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

{{ Form::bs_simple_text('Тип подключения:', $boiler->gateway_type) }}

@if($boiler->gateway_type == \App\Models\HomeObject::GATEWAY_HTTP)
    {{ Form::bs_autoselect('gateway_id', 'Контроллер*:', $devices, old('gateway_id', $boiler->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}
@else
    {{ Form::bs_autoselect('gateway_id', 'Устройство*:', $modbusSlavers, old('gateway_id', $boiler->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}
@endif

{{ Form::bs_autoselect('id_outside_thermostat', 'Уличный датчик температуры:', $termostats, old('id_outside_thermostat', $boiler->id_outside_thermostat), false, false, [], null) }}


{{ Form::bs_radio('thermostat', 'Состояние:', [0 => 'автономная работа', 1 => 'управляется сервером'], $boiler->thermostat, ['required' => true]) }}
{{ Form::bs_radio('boiler', 'Режим работы котла:', [0 => 'только горячая вода', 1 => 'горячая вода и отопление'], $boiler->boiler, ['required' => true]) }}

{{ Form::bs_text('target_water_temp', 'Темп. контура ГВС,°C*:', null, ['required' => true]) }}

<div style="height: 10px;">&nbsp;</div>
<hr>
<div style="height: 40px;">&nbsp;</div>



{{ Form::bs_simple_text('Подача:', $boiler->csupply ? $boiler->csupply.' °C' : '0°C' ) }}
{{ Form::bs_simple_text('Обратка:', $boiler->creturn ? $boiler->creturn.' °C' : '0°C') }}
{{ Form::bs_simple_text('Температура ГВС:', $boiler->water_temp ? $boiler->water_temp.' °C' : '0°C') }}
{{ Form::bs_simple_text('Горелка:', $boiler->burner ? $boiler->burner : '0') }}
{{ Form::bs_simple_text('Горелка ГВС:', $boiler->burner_GVS ? $boiler->burner_GVS : '0') }}
{{ Form::bs_simple_text('Модуляция горелки:', $boiler->burner_modulation ? $boiler->burner_modulation.' %' : '0%') }}
{{ Form::bs_simple_text('Состояние насоса:', $boiler->pump_status ? $boiler->pump_status : '0') }}
{{ Form::bs_simple_text('Давление теплоносителя:', $boiler->pressure ? $boiler->pressure : '0' ) }}
{{ Form::bs_simple_text('Установленная температура отопления:', $boiler->target_heat_temp ? $boiler->target_heat_temp.' °C' : '0°C' ) }}
{{ Form::bs_simple_text('Установленная температура ГВС:', $boiler->target_water_temp ? $boiler->target_water_temp.' °C' : '0°C' ) }}
