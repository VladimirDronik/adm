{{ Form::bs_radio('mode', 'Режим*:', $modes, old('mode', 0), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальное значение*:', old('optimal', 900), ['min' => 400, 'max' => 5000, 'required' => true], 'Значение, которое должно быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 50), ['min' => 0, 'max' => 100, 'required' => true]) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийное значение*:', old('min_alarm', 400), ['min' => 0, 'max' => 1000, 'required' => true], '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийное значение*:', old('max_alarm', 1400), ['min' => 1000, 'max' => 5000, 'required' => true], '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room'), false, false) }}
