<br>
{{ Form::bs_title('Действие при замыкании') }}

{{ Form::bs_autoselect('object_on', 'Объект:', $objects, old('object_on', $object_on),
    false, false, [], null, 'Объект, на который воздействуем') }}

{{ Form::bs_autoselect('method_on', 'Метод:', $methods_on, old('method_on', $method_on),
    false, false, [], null, 'Метод объекта при замыкании контакта') }}

<div class="form-group row" id="param_method_on_div"
     @if(is_null($drycontact->param_method_on) && !old('method_on')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="param_method_on"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="param_method_on_label" for="param_method_on">
                {{ optional($drycontact->emethod_on)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="param_method_on" name="param_method_on"
                       type="text" value="{{ old('param_method_on', $drycontact->param_method_on) }}">
            </div>
        </div>
    </div>
</div>


{{ Form::bs_title('Действие при размыкании') }}

{{ Form::bs_autoselect('object_off', 'Объект:', $objects, old('object_off', $object_off),
    false, false, [], null, 'Объект, на который воздействуем') }}

{{ Form::bs_autoselect('method_off', 'Метод:', $methods_off, old('method_off', $method_off),
    false, false, [], null, 'Метод объекта при размыкании контакта') }}

<div class="form-group row" id="param_method_off_div"
     @if(is_null($drycontact->param_method_off) && !old('method_off')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="param_method_off"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="param_method_off_label" for="param_method_off">
                {{ optional($drycontact->emethod_off)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="param_method_off" name="param_method_off"
                       type="text" value="{{ old('param_method_off', $drycontact->param_method_off) }}">
            </div>
        </div>
    </div>
</div>

<br>

