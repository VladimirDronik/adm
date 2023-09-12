<br>
{{ Form::bs_simple_text('ID объекта:', $termostat->iobject['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix"></label>

    <div class="col-sm-9">
        <input type="hidden" name="id_object" value="{{ $termostat->id_object }}">
        <div class="btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-success btn-sm @if($termostat->placetype == 'port') active @endif">
                <input type="radio" name="placetype_radio" autocomplete="off" value="port"> На порту
            </label>

            <label class="btn btn-success btn-sm @if($termostat->placetype == '1wbus') active @endif">
                <input type="radio" name="placetype_radio" autocomplete="off" value="1wbus"> На шине
            </label>

            <label class="btn btn-success btn-sm @if($termostat->placetype == 'usensor') active @endif">
                <input type="radio" name="placetype_radio" autocomplete="off" value="usensor"> На унив. датчике
            </label>

            <input type="hidden" id="placetype" name="placetype" value="{{$termostat->placetype}}">
        </div>
    </div>
</div>

<div class="col-sm-12 pr-0 mt-4" id="single_port_div" @if(($termostat->placetype != 'port') && ($termostat->placetype != '1wbus') ) style="display: none;" @endif>
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId), false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', is_null($portId) ? 0 : $portId), false, false, [], null) }}
</div>

<div class="col-sm-12 pr-0 mt-4" id="1wbus_port_div" @if($termostat->placetype != '1wbus') style="display: none;" @endif>
    {{ Form::bs_text('id_termometr', 'Код:', null, [], 'Уникальный ID термодатчика. Например, ff750c311703') }}
</div>

<div class="col-sm-12 pr-0 mt-4" id="usensor_div" @if($termostat->placetype != 'usensor') style="display: none;" @endif>
    {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id', is_null($termostat->usensor_id) ? 0 : $termostat->usensor_id), false, false, [], null) }}
</div>

@include('messages.two')