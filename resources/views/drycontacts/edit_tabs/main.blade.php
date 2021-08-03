
<br>
{{ Form::bs_simple_text('ID:', $drycontact->object['id'] ) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($drycontact->object && $drycontact->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$drycontact->id_object]) }}">
                    {{ $drycontact->object->name }}
                    @if($drycontact->object && $drycontact->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $drycontact->id_object }}">
@else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $drycontact->id_object), false, false, ['required' => true]) }}
@endif


<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
       false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
        false, false, [], null) }}
</div>

        @include('messages.two')
