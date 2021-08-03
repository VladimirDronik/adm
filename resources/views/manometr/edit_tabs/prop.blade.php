
<br>

{{ Form::bs_text('calibration', 'Калибровка*:', old('max_threshold', $manometr->calibration), ['required' => true],
                             '') }}

{{ Form::bs_number('low_value', 'Нижний аварийный порог*:', old('low_value', $manometr->low_value), ['min' => 0, 'max' => 1000, 'required' => true]) }}

{{ Form::bs_number('high_value', 'Верхний аварийный порог*:', old('high_value', $manometr->high_value), ['min' => 0, 'max' => 5000, 'required' => true],
                          '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $manometr->room), false, false) }}