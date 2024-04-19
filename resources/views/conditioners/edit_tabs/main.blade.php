{{ Form::bs_simple_text('ID объекта:', $conditioner->id_object) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

{{ Form::bs_autoselect('modbus_slaver_id', 'Модбас шлюз:', $modbusSlavers, old('modbus_slaver_id', $conditioner->modbus_slaver_id), false, false, ['required' => true], null) }}

{{ Form::bs_autoselect('id_room', 'Помещение:', $rooms, old('id_room', $conditioner->id_room), false, false, []) }}
