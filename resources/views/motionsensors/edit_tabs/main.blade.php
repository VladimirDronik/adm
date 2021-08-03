
<br>
{{ Form::bs_simple_text('ID:', $motionsensor->id) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($motionsensor->object && $motionsensor->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$motionsensor->id_object]) }}">
                    {{ $motionsensor->object->name }}
                    @if($motionsensor->object && $motionsensor->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $motionsensor->id_object }}">
    @else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $motionsensor->id_object), false, false, ['required' => true]) }}
    @endif

    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId),
                              false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('portId', is_null($portId) ? 0 : $portId),
        false, false, [], null) }}

        @include('messages.two')
