
<br>

{{ Form::bs_number('optimal', 'Оптимальная температура*:', null, ['min' => 0, 'max' => 40, 'required' => true],
    'Температура, которая должна быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $termostat->gisteresis), ['min' => 0, 'max' => 10, 'required' => true]) }}
{{ Form::bs_radio('thermostat', 'Режим*:', $types, old('thermostat', $termostat->thermostat), ['required' => true]) }}

{{ Form::bs_number('min_threshold', 'Минимальная температура*:', old('min_threshold', $termostat->min_threshold), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}
{{ Form::bs_number('max_threshold', 'Максимальная температура*:', old('max_threshold', $termostat->max_threshold), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}
{{ Form::bs_number('min_alarm', 'Мин. аварийная температура*:', old('min_alarm', $termostat->min_alarm), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийная температура*:', old('max_alarm', $termostat->max_alarm), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', is_null($termostat->room) ? 0 : $termostat->room ), false, false) }}



