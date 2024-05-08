{{ Form::bs_radio('mode', 'Режим*:', $types, old('mode', 0), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальная освещенность*:', old('optimal', 10), ['min' => 0, 'max' => 54612, 'required' => true], 'Освещенность, которая должна быть в помещении') }}

{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 10), ['min' => 0, 'max' => 1000, 'required' => true]) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийная освещенность*:', old('min_alarm', 0), ['min' => 0, 'max' => 188000, 'required' => true], '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийная освещенность*:', old('max_alarm', 54612), ['min' => 0, 'max' => 188000, 'required' => true], '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room'), false, false) }}
