{{ Form::bs_radio('mode', 'Режим*:', $modes, old('mode', $pressurestat->mode), ['required' => true]) }}
{{ Form::bs_radio('type_sensor', 'Тип*:', $sensorTypes, old('type_sensor', $pressurestat->type_sensor), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальное давление*:', old('optimal', $pressurestat->optimal), ['min' => 0, 'max' => 2000, 'required' => true], 'Давление, которое должно быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $pressurestat->gisteresis), ['min' => 0, 'max' => 100, 'required' => true]) }}

{{ Form::bs_simple_text('Минимальное давление:', $pressurestat->min_threshold) }}
{{ Form::bs_simple_text('Максимальное давление:', $pressurestat->max_threshold) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийное давление*:', old('min_alarm', $pressurestat->min_alarm), ['min' => 0, 'max' => 10000, 'required' => true], '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийное давление*:', old('max_alarm', $pressurestat->max_alarm), ['min' => 0, 'max' => 10000, 'required' => true], '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $pressurestat->room), false, false) }}
