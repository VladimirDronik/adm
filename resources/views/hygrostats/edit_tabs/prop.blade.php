
<br>

{{ Form::bs_number('optimal', 'Оптимальная влажность*:', null, ['min' => 0, 'max' => 100, 'required' => true],
    'Влажность, которая должна быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $hygrostat->gisteresis), ['min' => 0, 'max' => 10, 'required' => true]) }}
{{ Form::bs_radio('type', 'Режим*:', $types, old('hygrostat', $hygrostat->type), ['required' => true]) }}

{{--{{ Form::bs_number('min_threshold', 'Минимальная температура*:', old('min_threshold', $hygrostat->min_threshold), ['min' => 0, 'max' => 40, 'required' => true],--}}
{{--    '') }}--}}
{{--{{ Form::bs_number('max_threshold', 'Максимальная температура*:', old('max_threshold', $hygrostat->max_threshold), ['min' => 0, 'max' => 40, 'required' => true],--}}
{{--    '') }}--}}
{{ Form::bs_number('min_alarm', 'Мин. влажность аварии*:', old('min_alarm', $hygrostat->min_alarm), ['min' => 0, 'max' => 100, 'required' => true],
    '') }}
{{ Form::bs_number('max_alarm', 'Макс. влажность аварии*:', old('max_alarm', $hygrostat->max_alarm), ['min' => 0, 'max' => 100, 'required' => true],
    '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', is_null($hygrostat->room) ? 0 : $hygrostat->room ), false, false) }}



