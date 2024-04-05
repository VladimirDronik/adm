{{ Form::bs_radio('mode', 'Режим*:', $modes, old('mode', 0), ['required' => true]) }}
{{ Form::bs_radio('type_sensor', 'Тип*:', $sensorTypes, old('type_sensor', \App\Models\Pressurestat::TYPE_BMX280), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальное давление*:', old('optimal', 760), ['min' => 0, 'max' => 2000, 'required' => true], 'Давление, которое должно быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 5), ['min' => 0, 'max' => 100, 'required' => true]) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийное давление*:', old('min_alarm', 600), ['min' => 0, 'max' => 10000, 'required' => true], '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийное давление*:', old('max_alarm', 820), ['min' => 0, 'max' => 10000, 'required' => true], '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room'), false, false) }}
