
<br>
{{ Form::bs_simple_text('ID объекта:', $lamp->object['id']) }}

{{ Form::bs_simple_text('Тип:', $lamp->rus_type) }}

{{ Form::bs_simple_text('Тип подключения:', \App\Models\HomeObject::getGatewayNameByType($lamp->gateway_type)) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if($lamp->gateway_type == \App\Models\HomeObject::GATEWAY_HTTP)
    {{ Form::bs_autoselect('gateway_id', 'Контроллер*:', $devices, old('gateway_id', $lamp->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}

    {{ Form::bs_autoselect('port_id', 'Порт*:', [], old('port_id'), false, false, ['required' => true], null, null, 3, false, true) }}

    <hr>
    <div class="form-group row ">
        <label class="control-label text-right col-md-3 label-fix" for="name">
            <strong>Диммируется:</strong>
        </label>
        <div class="col-md-9 pt-2">
            <input type="checkbox" name="is_dimmer" @if($lamp->type == \App\Models\Lamp::TYPE_DIMMER) checked @endif >
        </div>
    </div>

    <div id='dimmer_fields_div' hidden>
        {{ Form::bs_number('value', 'Значение*:', old('value',  $lamp->value), ['required' => true, 'max' => 127]) }}

        {{ Form::bs_number('speed', 'Скорость*:', old('speed', $lamp->speed), ['required' => true, 'min' => 0, 'max' => 127]) }}
    </div>
@else
    {{ Form::bs_autoselect('gateway_id', 'Устройство*:', $modbusSlavers, old('gateway_id', $lamp->gateway_id), false, false, ['required' => true], null, null, 3, false, true) }}

    {{ Form::bs_autoselect('register_id', 'Регистр:', [], old('register_id'), false, false, [], null, 'Оставьте поле пустым, если не хотите менять регистр у методов', 3, false, false) }}

    <hr>
    <div class="form-group row ">
        <label class="control-label text-right col-md-3 label-fix" for="name">
            <strong>Диммируется:</strong>
        </label>
        <div class="col-md-9 pt-2">
            <input type="checkbox" name="is_dimmer" @if($lamp->type == \App\Models\Lamp::TYPE_DIMMER) checked @endif >
        </div>
    </div>
@endif

@include('messages.two')