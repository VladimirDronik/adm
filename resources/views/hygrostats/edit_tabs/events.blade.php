{{ Form::bs_title('События при проверке датчика влажности') }}

{{ Form::bs_autoselect('object', 'Объект влияния:', $objects, old('object', $hygrostat->object), false, false, [], null, 'Объект, у которого меняем состояние') }}

{{ Form::bs_autoselect('method_on', 'Действие при включении:', $methods, old('method_on', $hygrostat->method_on), false, false, [], null, 'Метод объекта воздействия при срабатывании датчика влажности на включение') }}

<div class="form-group row" id="method_on_params_div" @if(is_null($hygrostat->method_on_params) && !old('method_on')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_on_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_on_params_label" for="method_on_params">
                {{ optional($hygrostat->emethod_on)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_on_params" name="method_on_params" type="text" value="{{ old('method_on_params', $hygrostat->method_on_params) }}">
            </div>
        </div>
    </div>
</div>

{{ Form::bs_autoselect('method_off', 'Действие при выключении:', $methods, old('method_off', $hygrostat->method_off), false, false, [], null, 'Метод объекта воздействия при срабатывании датчика влажности на выключение') }}

<div class="form-group row" id="method_off_params_div" @if(is_null($hygrostat->method_off_params) && !old('method_off')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 label-fix" for="method_off_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_off_params_label" for="method_off_params">
                {{ optional($hygrostat->emethod_off)->params }}*:
            </label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_off_params" name="method_off_params" type="text" value="{{ old('method_off_params', $hygrostat->method_off_params) }}">
            </div>
        </div>
    </div>
</div>
