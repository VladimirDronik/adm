<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix" for="percent">Смена состояния:</label>
    <div class="col-md-6">
        <button type="button" class="btn btn-success m-b-10 m-l-5" id="openBtn">Открыть</button>
        <button type="button" class="btn btn-success m-b-10 m-l-5" id="closeBtn">Закрыть</button>
        @if($curtain->place == \App\Models\Curtain::PLACE_RS485)
            <button type="button" class="btn btn-danger m-b-10 m-l-5" id="stopBtn">Стоп</button>
        @endif
    </div>
</div>

@if($curtain->place == \App\Models\Curtain::PLACE_RS485)
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="percent">Процент открытия:</label>
        <div class="col-md-2">
            <input class="form-control" autocomplete="off" name="percent" value="{{ old('percent', $curtain->percent) }}">
        </div>
        <button type="button" class="btn btn-success m-b-10 m-l-5" id="setPercentBtn">Задать</button>
    </div>
@endif
