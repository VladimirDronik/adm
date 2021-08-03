
<br>

{{ Form::bs_text('calibration', 'Калибровка*:', old('max_threshold', $carbmonoxide->calibration), ['required' => true],
                               '') }} <div style="height: 60px;">&nbsp;</div>

{{ Form::bs_number('low_value', 'Нижний аварийный порог*:', old('low_value', $carbmonoxide->low_value), ['min' => 0, 'max' => 1000, 'required' => true]) }}


{{ Form::bs_number('high_value', 'Верхний аварийный порог*:', old('high_value', $carbmonoxide->high_value), ['min' => 0, 'max' => 5000, 'required' => true],
                           '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $carbmonoxide->room), false, false) }}
