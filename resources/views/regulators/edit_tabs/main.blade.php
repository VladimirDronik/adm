<div class="form-body">
    {{ Form::bs_simple_text('ID объекта:', $regulator->object_id) }}

    {{ Form::bs_text('name', 'Название*:', old('name', $regulator->object->name), ['required' => true]) }}

    {{ Form::bs_autoselect('room', 'Помещение*:', $rooms, old('room', $regulator->room), false, false, [], null) }}

    {{ Form::bs_radio('status', 'Состояне:', ['on' => 'Вкл', 'off' => 'Выкл'], old('status', $regulator->object->status), []) }}

    {{ Form::bs_text('setpoint', 'Уставка*:', old('setpoint', $regulator->setpoint), ['required' => true]) }}

    @if(!$regulator->source)
        {{ Form::bs_text('hysteresis', 'Гистерезис*:', old('hysteresis', $regulator->hysteresis), ['required' => true]) }}

        {{ Form::bs_autoselect('sensor', 'Датчик*:', $sensors, old('sensor', $regulator->sensorsParam->object_id), false, false, [], null) }}

        {{ Form::bs_autoselect('sensor_param', 'Параметр*:', [], old('sensor_param'), false, false, [], null) }}

        <br>
        <br>
        {{ Form::bs_title('Метод при значении выше уставки') }}

        {{ Form::bs_autoselect('higher_object', 'Объект:', $objects, $regulator->higherMethod->id_object, false, false, [], null) }}

        {{ Form::bs_autoselect('higher_method', 'Метод:', $higherMethods, $regulator->higher_method, false, false, [], null) }}

        <div class="form-group row" id="higher_method_params_div" @if(!$regulator->higher_method_params) style="display: none;" @endif>
            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="higher_method_params"></label>
            <div class="col-md-9 pr-0">
                <div class="form-group row ">
                    <label class="control-label text-right col-md-6 label-fix" id="higher_method_params_label" for="higher_method_params">{{ $regulator->higherMethod->params ?: '...'}}</label>
                    <div class="col-md-6">
                        <input class="form-control" autocomplete="off" id="higher_method_params" name="higher_method_params" type="text" value="{{ old('higher_method_params', $regulator->higher_method_params) }}">
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <br>
        <br>
        {{ Form::bs_title('Метод при значении меньше уставки') }}

        {{ Form::bs_autoselect('lower_object', 'Объект:', $objects, $regulator->lowerMethod->id_object, false, false, [], null) }}

        {{ Form::bs_autoselect('lower_method', 'Метод:', $lowerMethods, $regulator->lower_method, false, false, [], null) }}

        <div class="form-group row" id="lower_method_params_div" @if(!$regulator->lower_method_params) style="display: none;" @endif>
            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="lower_method_params"></label>
            <div class="col-md-9 pr-0">
                <div class="form-group row ">
                    <label class="control-label text-right col-md-6 label-fix" id="lower_method_params_label" for="lower_method_params">{{ $regulator->lowerMethod->params ?: '...'}}</label>
                    <div class="col-md-6">
                        <input class="form-control" autocomplete="off" id="lower_method_params" name="lower_method_params" type="text" value="{{ old('lower_method_params', $regulator->lower_method_params) }}">
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <br>
        <br>
        {{ Form::bs_title('Аварийный метод') }}

        {{ Form::bs_autoselect('fallback_object', 'Объект:', $objects, $regulator->fallbackMethod?->id_object, false, false, [], null) }}

        {{ Form::bs_autoselect('fallback_method', 'Метод:', $fallbackMethods, $regulator->fallback_method, false, false, [], null) }}

        <div class="form-group row" id="fallback_method_params_div" @if(!$regulator->fallback_method_params) style="display: none;" @endif>
            <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="fallback_method_params"></label>
            <div class="col-md-9 pr-0">
                <div class="form-group row ">
                    <label class="control-label text-right col-md-6 label-fix" id="fallback_method_params_label" for="fallback_method_params">{{ $regulator->fallbackMethod?->params ?: '...'}}</label>
                    <div class="col-md-6">
                        <input class="form-control" autocomplete="off" id="fallback_method_params" name="fallback_method_params" type="text" value="{{ old('fallback_method_params', $regulator->fallback_method_params) }}">
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <br>
        <br>
    @else
        <input type="text" name="independent_device" hidden value="1">
        @if($regulator->source == 'modbus')
            <input type="text" name="source" hidden value="modbus">
            {{ Form::bs_autoselect('modbus_slaver', 'Устройство*:', $slavers, old('modbus_slaver', $regulator->source_id), false, false, [], null) }}

            {{ Form::bs_autoselect('modbus_register', 'Регистр*:', [], old('modbus_register'), false, false, [], null) }}
        @elseif($regulator->source == 'megad')
            <input type="text" name="source" hidden value="megad">
            {{ Form::bs_autoselect('device', 'Контроллер*:', $devices, old('device', $device->id), false, false, [], null) }}

            {{ Form::bs_autoselect('port', 'Порт*:', [], old('port'), false, false, [], null) }}
        @endif
    @endif

    {{ Form::bs_text('min_setpoint', 'Минимальное значение уставки*:', old('min_setpoint', $regulator->min_setpoint), []) }}

    {{ Form::bs_text('max_setpoint', 'Максимальное значение уставки*:', old('max_setpoint', $regulator->max_setpoint), []) }}

    {{ Form::bs_title('Текущее значение датчика') }}

    <div class="form-group row ">
        <label class="control-label text-right col-md-3 label-fix">{{ $regulator->sensorsParam->name }}:</label>
        <div class="col-md-9 d-flex align-items-center">
            {{ $regulator->sensorsParam->value ? ($regulator->sensorsParam->value . ' ' . $regulator->sensorsParam->unit_name) : '' }}
        </div>
    </div>
    <div class="form-group row ">
        <label class="control-label text-right col-md-3 label-fix">Датчик:</label>
        <div class="col-md-9 d-flex align-items-center">
            <a href="{{ route('sensors.edit', [$regulator->sensorsParam->object_id]) }}">
                {{ $regulator->sensorsParam->object->name }}
            </a>
        </div>
    </div>
</div>
