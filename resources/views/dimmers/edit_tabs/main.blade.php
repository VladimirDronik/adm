<br>

{{ Form::bs_simple_text('ID объект:', $dimmer->object['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<input type="hidden" name="id_object" value="{{ $dimmer->id_object }}">

<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
       false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
        false, false, [], null) }}
</div>