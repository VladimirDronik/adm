{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

{{ Form::bs_autoselect('usensor_id', 'I2C датчик:', $usensors, old('usensor_id'), false, false, [], null) }}
