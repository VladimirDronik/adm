
<br>
{{ Form::bs_radio('type', 'Тип выключателя*:', $types, old('type', $switch->type), ['required' => true]) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($switch->object && $switch->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$switch->id_object]) }}">
                    {{ $switch->object->name }}
                    @if($switch->object && $switch->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $switch->id_object }}">
@else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $switch->id_object), false, false, ['required' => true]) }}
@endif

<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
       false, false, [], null) }}


    <div id='port_id_div' @if ($idPort==null) style="display: none" @endif>
        {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
          false, false, [], null) }}
    </div>

    <div id='hitepro_devices_div' @if ($hp_device==null) style="display: none" @endif>
        {{ Form::bs_autoselect('hitepro_devices', 'Устройство:', $hp_devices, old('hitepro_devices', $hp_device),
            false, false, [], null) }}
    </div>
</div>

{{ Form::bs_simple_text('ID объекта:', $switch->object['id']) }}
<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix" for="">
        Тип выключателя:     </label>
    <div class="col-md-9">
        <div class="mt-2">
            {{ $switch->rus_type }}
        </div>
    </div>
</div>

@include('messages.two')