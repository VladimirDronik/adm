<br>

{{ Form::bs_simple_text('ID объект:', $dimmer->object['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($dimmer->object && $dimmer->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$dimmer->id_object]) }}">
                    {{ $dimmer->object->name }}
                    @if($dimmer->object && $dimmer->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $dimmer->id_object }}">
@else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $dimmer->id_object), false, false, ['required' => true]) }}
@endif


<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
       false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
        false, false, [], null) }}
</div>