<br>
{{ Form::bs_simple_text('ID объекта:', $lock->object['id']) }}
<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix" for="">
        Тип:     </label>
    <div class="col-md-9">
        <div class="mt-2">
            {{ $lock->rus_type }}
        </div>
    </div>
</div>

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($lock->object && $lock->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$lock->id_object]) }}">
                    {{ $lock->object->name }}
                    @if($lock->object && $lock->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $lock->id_object }}">
    @else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $lock->id_object), false, false, ['required' => true]) }}
    @endif


    <div class="col-sm-12 pr-0 mt-4">
        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
           false, false, [], null) }}

        <div id='port_id_div' @if ($place=='Hite-pro') style="display: none" @endif>
            {{ Form::bs_autoselect('port_id_open', $label_port , $ports, old('port_id_open', $idPort_open),
                false, false, [], null) }}
            <div id="portclosediv" @if ($lock->type != 'Electromechanical') style="display: none" @endif>
            {{ Form::bs_autoselect('port_id_close', 'Порт на закрытие:', $ports, old('port_id_close', $idPort_close),
                false, false, [], null) }}
            </div>
        </div>

        <div id='hitepro_devices_div' @if ($place=='port') style="display: none" @endif>
            {{ Form::bs_autoselect('hitepro_device_open', $label_hitepro, $hp_devices, old('hiteProDevice_open', $hp_device_open),
                false, false, [], null) }}

            <div id="deviceclosediv"  @if ($lock->type != 'Electromechanical') style="display: none" @endif>
            {{ Form::bs_autoselect('hitepro_device_close', 'Устройство на закрытие:', $hp_devices, old('hiteProDevice_close', $hp_device_close),
                false, false, [], null) }}
            </div>
        </div>

        <div id="timediv"  @if ($lock->type != 'Magnetic') style="display: none" @endif>
            {{ Form::bs_text('time', 'Время открытия в секундах:', null) }}
        </div>

        <input type="hidden" name="place" id="place" value="@if ($ports==null) Hite-pro @else port @endif">
    </div>

    @include('messages.two')