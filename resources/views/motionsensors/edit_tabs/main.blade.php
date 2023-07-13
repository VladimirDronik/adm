
<br>
{{ Form::bs_simple_text('ID:', $motionsensor->id) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<input type="hidden" name="id_object" value="{{ $motionsensor->id_object }}">

{{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId), false, false, [], null) }}

{{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('portId', is_null($portId) ? 0 : $portId), false, false, [], null) }}

@include('messages.two')
