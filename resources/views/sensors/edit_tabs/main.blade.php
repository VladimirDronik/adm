{{ Form::bs_text('name', 'Название:', old('name', $sensorObject->name), ['required' => true]) }}

{{ Form::bs_autoselect('room', 'Помещение:', $rooms, old('room', $sensorSettings->where('name', 'room')->first()?->value), false, false, []) }}

{{ Form::bs_simple_text('Тип:', $sensorSettings->where('name', 'type')->first()?->value) }}

{{ Form::bs_simple_text('Тип источника данных:', $sensorSettings->where('name', 'source')->first()?->value) }}

@if($sensorSettings->where('name', 'source')->first()?->value != 'mqtt')
    {{ Form::bs_autoselect('source_id', 'Источник данных:', $sources, old('source_id', $sensorSettings->where('name', 'source_id')->first()?->value), false, false, ['required' => true]) }}
@endif

@if($sensorSettings->where('name', 'source')->first()?->value == 'megad')
    {{ Form::bs_simple_text('Тип подключения:', $sensorSettings->where('name', 'connection')->first()?->value) }}

    @if($sensorSettings->where('name', 'connection')->first()?->value != 'i2c')
        {{ Form::bs_autoselect('port', 'Порт:', [], old('port', $sensorSettings->where('name', 'port')->first()?->value), false, false, ['required' => true]) }}
    @else
        {{ Form::bs_autoselect('sda', 'Порт SDA:', [], old('sda', $sensorSettings->where('name', 'sda')->first()?->value), false, false, ['required' => true]) }}

        {{ Form::bs_autoselect('scl', 'Порт SCL:', [], old('scl', $sensorSettings->where('name', 'scl')->first()?->value), false, false, ['required' => true]) }}
    @endif
@endif

{{ Form::bs_title('Параметры') }}
<div class="form-group row">
    <label class="col-md-3"><i>Название</i></label>
    <div class="col-md-3"><i>Значение</i></div>
    <div class="col-md-3"><i>Время последнего значения</i></div>
    <div class="col-md-2 text-right"></div>
</div>
<div id="sensorsParams_div">
    @foreach($sensorObject->sensorsParams as $sensorsParam)
        <div class="form-group row" id="divSensorsParam{{$sensorsParam->id}}">
            <label class="col-md-3" id="type{{$sensorsParam->id}}">
                {{ $sensorsParam->name }}
            </label>
            <div class="col-md-3" id="value{{$sensorsParam->id}}">
                {{ $sensorsParam->value ? ($sensorsParam->value . ' ' . $sensorsParam->unit_name) : '' }}
            </div>
            <div class="col-md-3" id="timestamp{{$sensorsParam->id}}">
                {{ $sensorsParam->timestamp }}
            </div>
            <div class="col-md-2 text-right">
                @if($sensorsParam->graph)
                    <button type="button" data-id="{{ $sensorsParam->id }}" data-name="{{ $sensorsParam->name }}" class="btn btn-info btn-sm btn-rounded graph_btn">
                        <i class="fa fa-bar-chart"></i>
                    </button>
                @endif
                <button type="button"
                    data-id="{{ $sensorsParam->id }}"
                    data-name="{{ $sensorsParam->name }}"
                    data-param="{{ $sensorsParam->param }}"
                    data-param_name="{{ $sensorsParam->param_name }}"
                    data-get_param="{{ $sensorsParam->get_param }}"
                    data-value="{{ $sensorsParam->value }}"
                    data-unit_name="{{ $sensorsParam->unit_name }}"
                    data-units="{{ $sensorsParam->units }}"
                    data-accuracy="{{ $sensorsParam->accuracy }}"
                    data-graph="{{ $sensorsParam->graph }}"
                    data-min_range="{{ $sensorsParam->min_range }}"
                    data-max_range="{{ $sensorsParam->max_range }}"
                    data-min_alarm="{{ $sensorsParam->min_alarm }}"
                    data-max_alarm="{{ $sensorsParam->max_alarm }}"
                    data-timestamp="{{ $sensorsParam->timestamp }}"
                    class="btn btn-info btn-sm btn-rounded edit_btn">
                    <i class="fa fa-cog fa-lg"></i>
                </button>
                @if($sensorSettings->where('name', 'type')->first()?->value == 'custom' || $sensorSettings->where('name', 'connection')->first()?->value == '1wbus')
                    <button type="button" data-id="{{ $sensorsParam->id }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                        <i class="fa fa-trash fa-lg"></i>
                    </button>
                @endif
            </div>
        </div>
    @endforeach
</div>
<div class="form-group row">
    <div class="col-md-12 text-left">
        @if($sensorSettings->where('name', 'type')->first()?->value == 'custom')
            <button id="add_btn" type="button" class="btn btn-primary">
                <i class="fa fa-plus fa-lg"></i> Добавить параметр
            </button>
        @endif

        @if($sensorSettings->where('name', 'connection')->first()?->value == '1wbus' && $addressParamsCount !== null && $addressParamsCount < 7)
            <button id="add_address_btn" type="button" class="btn btn-primary">
                <i class="fa fa-plus fa-lg"></i> Добавить адрес
            </button>
        @endif
    </div>
</div>
