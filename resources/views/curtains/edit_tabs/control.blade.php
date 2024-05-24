{{ Form::bs_radio('status', 'Смена состояния:', ['open' => 'Открыть', 'close' => 'Закрыть'], old('status', $curtain->object?->status)) }}

@if($curtain->place == \App\Models\Curtain::PLACE_RS485)
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="percent">Процент открытия:</label>
        <div class="col-md-2">
            <input class="form-control" autocomplete="off" min="0" max="100" name="percent" type="number" value="{{ old('percent', $curtain->percent) }}">
        </div>
        <button type="button" class="btn btn-success m-b-10 m-l-5" id="setPercentBtn">Задать</button>
    </div>
@endif
