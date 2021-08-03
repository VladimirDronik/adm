<br>


{{ Form::bs_text('impulse', 'Значение за один импульс (в '.$count->unit.')*:', old('impulse', $count->impulse), ['required' => true]) }}
{{ Form::bs_text('today_value', 'Значение за сегодня*:', old('today_value', $count->today_value), ['required' => true]) }}
{{ Form::bs_text('total_value', 'Общее значение*:', old('total_value', $count->total_value), ['required' => true]) }}