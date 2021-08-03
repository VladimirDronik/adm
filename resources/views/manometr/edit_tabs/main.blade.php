
<br>
{{ Form::bs_simple_text('ID объекта:', $manometr->iobject['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}




<div class="form-group row ">
    <label class="control-label text-right col-md-3 label-fix" for="id_object">
        <strong>Размещение манометра:</strong>
    </label>

    <div class="col-md-6 pr-0 mt-4" id="single_port_div">
        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId),
           false, false, [], null) }}

        {{ Form::bs_autoselect('port', 'Порт:', $ports, old('port', is_null($port) ? 0 : $port),
            false, false, [], null) }}

    </div>
</div>

        @include('messages.two')
