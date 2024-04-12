{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

{{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id'), false, false, [], null) }}
