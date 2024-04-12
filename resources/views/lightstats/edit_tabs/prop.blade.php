{{ Form::bs_number('optimal', 'Оптимальная освещенность*:', null, ['min' => 0, 'max' => 54612, 'required' => true], 'Освещенность, которая должна быть в помещении') }}

{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $lightstat->gisteresis), ['min' => 0, 'max' => 1000, 'required' => true]) }}

{{ Form::bs_radio('mode', 'Режим*:', $types, old('mode', $lightstat->mode), ['required' => true]) }}

{{ Form::bs_simple_text('Минимальная освещенность:', $lightstat->min_threshold) }}
{{ Form::bs_simple_text('Максимальная освещенность:', $lightstat->max_threshold) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийная освещенность*:', old('min_alarm', $lightstat->min_alarm), ['min' => 0, 'max' => 54612, 'required' => true], '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийная освещенность*:', old('max_alarm', $lightstat->max_alarm), ['min' => 0, 'max' => 54612, 'required' => true], '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $lightstat->room), false, false) }}
