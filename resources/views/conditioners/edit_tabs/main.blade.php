{{ Form::bs_simple_text('ID объекта:', $conditioner->id_object) }}

{{ Form::bs_simple_text('Модбас шлюз:', $conditioner->modbusSlaver->name) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

{{ Form::bs_autoselect('id_room', 'Помещение:', $rooms, old('id_room', $conditioner->id_room), false, false, []) }}
