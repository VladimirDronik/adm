{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
{{ Form::bs_number('volume', 'Громкость звука:', old('volume', $yandexstation->volume), ['min' => 0, 'max' => 100, 'required' => false],'') }}
{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $yandexstation->room), false, false) }}
