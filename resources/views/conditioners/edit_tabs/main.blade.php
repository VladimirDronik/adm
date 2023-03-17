@if($conditioner->object && $conditioner->object->is_system)
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$conditioner->id_object]) }}">
                    {{ $conditioner->object->name }}
                    @if($conditioner->object && $conditioner->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $conditioner->id_object }}">
@else
    {{ Form::bs_autoselect('id_object', 'Объект:', $objects, old('id_object', $conditioner->id_object), false, false, ['required' => true]) }}
@endif

{{ Form::bs_simple_text('ID объекта:', $conditioner->object['id']) }}

{{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $conditioner->device_id), false, false, ['required' => true], null) }}

{{ Form::bs_autoselect('id_room', 'Помещение:', $rooms, old('id_room', $conditioner->id_room), false, false, ['required' => true]) }}

{{ Form::bs_text('wb_mir', 'Адрес WB-MIR:', old('wb_mir', $conditioner->wb_mir), ['required' => true], null, 3, 'wbMir') }}
