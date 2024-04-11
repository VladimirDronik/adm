{{ Form::bs_radio('mode', 'Режим*:', $modes, old('mode', $carbdioxide->mode), ['required' => true]) }}

{{ Form::bs_number('optimal', 'Оптимальное значение*:', old('optimal', $carbdioxide->optimal), ['min' => 400, 'max' => 5000, 'required' => true], 'Значение, которое должно быть в помещении') }}
{{ Form::bs_number('gisteresis', 'Гистерезис*:', old('gisteresis', $carbdioxide->gisteresis), ['min' => 0, 'max' => 100, 'required' => true]) }}

{{ Form::bs_simple_text('Минимальное значение:', $carbdioxide->min_threshold) }}
{{ Form::bs_simple_text('Максимальное значение:', $carbdioxide->max_threshold) }}

{{ Form::bs_number('min_alarm', 'Мин. аварийное значение*:', old('min_alarm', $carbdioxide->min_alarm), ['min' => 0, 'max' => 1000, 'required' => true], '') }}
{{ Form::bs_number('max_alarm', 'Макс. аварийное значение*:', old('max_alarm', $carbdioxide->max_alarm), ['min' => 1000, 'max' => 5000, 'required' => true], '') }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $carbdioxide->room), false, false) }}
