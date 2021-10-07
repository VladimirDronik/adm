<br>

{{ Form::bs_radio('type', 'Режим*:', $types, old('type', -1), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальная влажность*:', null, ['min' => 0, 'max' => 100, 'required' => true],
    'Влажность, которая должна быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', 1), ['min' => 0, 'max' => 10, 'required' => true]) }}


{{--{{ Form::bs_number('min_threshold', 'Минимально возможная влажность в помещении*:', old('min_threshold', 20), ['min' => 0, 'max' => 100, 'required' => true],--}}
{{--    '') }}--}}
{{--{{ Form::bs_number('max_threshold', 'Максимально возможная влажность в помещении*:', old('max_threshold', 60), ['min' => 0, 'max' => 100, 'required' => true],--}}
{{--    '') }}--}}
{{ Form::bs_number('min_alarm', 'Мин. влажность аварии*:', old('min_alarm', 0), ['min' => 0, 'max' => 100, 'required' => true],
    '') }}
{{ Form::bs_number('max_alarm', 'Макс. влажность аварии*:', old('max_alarm', 80), ['min' => 0, 'max' => 100, 'required' => true],
    '') }}


{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', -1), false, false) }}