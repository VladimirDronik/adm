{{ Form::bs_text('name', 'Название*:', old('name', $ledTape->name), ['required' => true]) }}

{{ Form::bs_autoselect('room', 'Размещение:', $rooms, old('room', $ledTape->room), false, false, []) }}

{{ Form::bs_simple_text('Тип ленты:', $ledTape->type) }}

{{ Form::bs_simple_text('Имя контроллера:', $ledTape->modbusSlaver?->name) }}

{{ Form::bs_simple_text('Канал подключения:', $ledTape->channel) }}

{{ Form::bs_simple_text('Состояние:', $ledTape->object->status) }}

{{ Form::bs_simple_text('Яркость:', ($ledTape->type == \App\Models\LedTape::TYPE_RGB || $ledTape->type == \App\Models\LedTape::TYPE_RGBW) ? $ledTape->v : $ledTape->w) }}

@if($ledTape->type == \App\Models\LedTape::TYPE_RGB || $ledTape->type == \App\Models\LedTape::TYPE_RGBW)
    <div class="form-group row ">
        <label class="control-label text-right col-md-3 label-fix">
            Цвет:
        </label>
        <div class="col-sm-9">
            <div style="display: inline-block; width: 100px; height: 50px; background-color: hsl({{ $ledTape->h }}, {{ $ledTape->hsvToHsl()['s'] }}%, {{ $ledTape->hsvToHsl()['l'] }}%)"></div>
            <ul style="display: inline-block;">
                <li>&nbsp;&nbsp; h = {{ $ledTape->h }}&deg</li>
                <li>&nbsp;&nbsp; s = {{ $ledTape->s }}%</li>
                <li>&nbsp;&nbsp; v = {{ $ledTape->v }}%</li>
            </ul>
        </div>
    </div>
@elseif($ledTape->type == \App\Models\LedTape::TYPE_CCT)
    {{ Form::bs_simple_text('Цветовая температура:', $ledTape->cct) }}
@endif

<input type="hidden" name="id_object" value="{{ $ledTape->id_object }}">
