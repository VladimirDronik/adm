
<br>
{{ Form::bs_simple_text('ID:', $drycontact->object['id'] ) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<input type="hidden" name="id_object" value="{{ $drycontact->id_object }}">

{{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice), false, false, [], null) }}

{{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort), false, false, [], null) }}

@include('messages.two')
