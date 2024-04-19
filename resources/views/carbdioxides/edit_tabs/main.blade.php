{{ Form::bs_simple_text('ID объекта:', $carbdioxide->id_object) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

{{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id', $carbdioxide->usensor_id), false, false, [], null) }}

@include('messages.two')
