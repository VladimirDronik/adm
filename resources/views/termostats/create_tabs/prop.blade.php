<br>

{{ Form::bs_radio('thermostat', 'Режим*:', $types, old('thermostat', -1), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальная температура*:', null, ['min' => 0, 'max' => 40, 'required' => true],
    'Температура, которая должна быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 1), ['min' => 0, 'max' => 10, 'required' => true]) }}


{{ Form::bs_number('min_threshold', 'Минимальная температура*:', old('min_threshold', 0), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}
{{ Form::bs_number('max_threshold', 'Максимальная температура*:', old('max_threshold', 30), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}
{{ Form::bs_number('min_alarm', 'Мин. аварийная температура*:', old('min_alarm', 0), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийная температура*:', old('max_alarm', 40), ['min' => 0, 'max' => 40, 'required' => true],
    '') }}


{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}