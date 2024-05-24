{{ Form::bs_radio('status', 'Состояние:', ['on' => 'Вкл', 'off' => 'Выкл'], old('status', $conditioner->object->status)) }}

<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix" for="temp">Температура:</label>
    <div class="col-md-2">
        <input class="form-control" autocomplete="off" min="{{ $tempSettings['min'] ?? 0 }}" max="{{ $tempSettings['max'] ?? 30 }}" name="temp" type="number" value="{{ old('temp', $conditioner->temp) }}">
    </div>
    <button type="button" class="btn btn-success m-b-10 m-l-5" id="setTempBtn">Задать</button>
</div>

{{ Form::bs_radio('mode', 'Режим:', $modeSettings, old('mode', $conditioner->mode)) }}

{{ Form::bs_radio('fan', 'Скорость вентилятора:', $fanSettings, old('fan', $conditioner->fan)) }}

@if(!empty($vdirSettings))
    {{ Form::bs_radio('vdir', 'Вертикальное направление:', $vdirSettings, old('vdir', $conditioner->vdir)) }}
@endif

@if(!empty($hdirSettings))
    {{ Form::bs_radio('hdir', 'Горизонтальное направление:', $hdirSettings, old('hdir', $conditioner->hdir)) }}
@endif
