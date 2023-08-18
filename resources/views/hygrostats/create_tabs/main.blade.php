<br>
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix"></label>

    <div class="col-sm-9">
        <div class="btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-success btn-sm active">
                <input type="radio" name="placetype_radio" autocomplete="off" value="usensor"> На унив. датчике
            </label>

            <label class="btn btn-success btn-sm">
                <input type="radio" name="placetype_radio" autocomplete="off" value="device"> Отдельное устройство
            </label>

            <input type="hidden" id="placetype" name="placetype" value="usensor">
        </div>
    </div>
</div>

<div class="col-sm-12 pr-0 mt-4" id="usensor_div" style="display: block;">
    {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id'), false, false, [], null) }}
</div>

<div class="col-sm-12 pr-0 mt-4" id="device_div" style="display: none" ;>
    {{ Form::bs_autoselect('HPController_id', 'Контроллер:', $HPControllers, old('HPController_id'), false, false, [], null) }}

    {{ Form::bs_autoselect('subdev_id', 'Гигростат:', [], old('subdev_id'), false, false, [], null) }}
</div>