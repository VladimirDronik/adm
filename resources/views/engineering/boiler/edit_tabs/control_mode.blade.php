{{ Form::bs_radio('mode', 'Режим управления температурой:', $boiler->getModes(), $boiler->mode, ['required' => true]) }}

<div id="manual_set_value">
    {{ Form::bs_text('set_value', 'Температура теплоносителя*:', old('set_value', $boiler->object->boilerManual->set_value), ['required' => false]) }}
</div>

<div id="AutoFieldsContainer">
    @if($boiler->object->boilerAuto->isNotEmpty())
        @foreach($boiler->object->boilerAuto as $boilerAuto)
            <div class="form-group row">
                <label class="control-label text-right col-md-3 label-fix">
                    <strong>Уличная температура:</strong>
                </label>
                <div class="col-md-2">
                    <input class="form-control" name="boiler_auto[{{ $boilerAuto->id }}][t_out]" autocomplete="off" value="{{ $boilerAuto->t_out }}" required>
                </div>
                <label class="control-label text-right col-md-3 label-fix">
                    <strong>Температура теплоносителя:</strong>
                </label>
                <div class="col-md-2">
                    <input class="form-control" name="boiler_auto[{{ $boilerAuto->id }}][t_water]" autocomplete="off" value="{{ $boilerAuto->t_water }}" required>
                </div>
                <div class="col-sm-2"><button type="button" id="deleteBoilerAuto{{ $boilerAuto->id }}" onclick="deleteBoilerAuto('{{ $boilerAuto->id }}')" class="deleteExtensionModule btn btn-outline-danger">Удалить</button></div>
            </div>
        @endforeach
    @endif
</div>
<button class="btn btn-success m-b-10 m-l-5" type="button" id="addAutoFieldsBtn">Добавить</button>