<br>
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}


<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix"></label>

    <div class="col-sm-9">
        <div class="btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-success btn-sm  active ">
                <input type="radio" name="placetype_radio" autocomplete="off" value="port"> На порту
            </label>

            <label class="btn btn-success btn-sm">
                <input type="radio" name="placetype_radio" autocomplete="off" value="1wbus"> На шине
            </label>

            <label class="btn btn-success btn-sm">
                <input type="radio" name="placetype_radio" autocomplete="off" value="usensor"> На унив. датчике
            </label>

            <label class="btn btn-success btn-sm">
                <input type="radio" name="placetype_radio" autocomplete="off" value="device"> Отдельное устройство
            </label>

            <input type="hidden" id="placetype" name="placetype" value="port">
        </div>
    </div>
</div>

<div class="col-sm-12 pr-0 mt-4" id="single_port_div">
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'), false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', [], old('port_id'), false, false, [], null) }}
</div>

<div class="col-sm-12 pr-0 mt-4" id="1wbus_port_div" style="display: none;">
    {{ Form::bs_text('id_termometr', 'Код:', null, [], 'Уникальный ID термодатчика. Например, ff750c311703') }}
</div>

<div class="col-sm-12 pr-0 mt-4" id="usensor_div" style="display: none;">
    {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id'), false, false, [], null) }}
</div>

<div class="col-sm-12 pr-0 mt-4" id="device_div" style="display: none" ;>
    {{ Form::bs_autoselect('HPController_id', 'Контроллер:', $HPControllers, old('HPController_id'), false, false, [], null) }}

    {{ Form::bs_autoselect('subdev_id', 'Термометр:', [], old('subdev_id'), false, false, [], null) }}
</div>