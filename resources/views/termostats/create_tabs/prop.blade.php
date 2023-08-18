<br>

{{ Form::bs_radio('thermostat', 'Режим*:', $types, old('thermostat', 1), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальная температура*:', old('optimal', 22), ['step' => '0.1', 'required' => true],
    'Температура, которая должна быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 1), ['step' => '0.1', 'required' => true]) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийная температура*:', old('min_alarm', 0), ['required' => true],
    '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийная температура*:', old('max_alarm', 40), ['required' => true],
    '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}
