<br>
{{ Form::bs_simple_text('ID объекта:', $curtain->object['id']) }}
{{ Form::bs_radio('type', 'Тип:', $types, old('type', $curtain->type), []) }}

<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix" for="">
        Тип управления:     </label>
    <div class="col-md-9">
        <div class="mt-2">
            {{ $curtain->rus_place }}
        </div>
    </div>
</div>

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($curtain->object && $curtain->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$curtain->id_object]) }}">
                    {{ $curtain->object->name }}
                    @if($curtain->object && $curtain->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $curtain->id_object }}">
    @else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $curtain->id_object), false, false, ['required' => true]) }}
    @endif


    <div class="col-sm-12 pr-0 mt-4">
        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
            false, false, [], null) }}

            @if ($curtain->place == \App\Models\Curtain::PLACE_PORT || $curtain->place == \App\Models\Curtain::PLACE_PHASE)

                {{ Form::bs_autoselect('port_id_open', 'Порт на открытие:', $ports, old('port_id_open', $curtain->port_open),
                    false, false, [], null) }}
                {{ Form::bs_autoselect('port_id_close', 'Порт на закрытие:', $ports, old('port_id_close', $curtain->port_close),
                    false, false, [], null) }}

            @else

                {{ Form::bs_text('address', 'Адрес:', old('address', $curtain->address), [], 'От 0 до 255') }}
                {{ Form::bs_text('group', 'Группа:', old('group', $curtain->group), [], 'От 0 до 255') }}

            @endif
            <input type="hidden" name="place" value="{{ $curtain->place }}">

            @if ($curtain->place == \App\Models\Curtain::PLACE_PHASE)
                {{ Form::bs_text('time', 'Время открытия или закрытия*:', old('time', $curtain->time), ['required' => true], 'В секундах') }}
            @endif
    </div>

    @include('messages.two')