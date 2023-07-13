<br>
{{ Form::bs_simple_text('ID объекта:', $lightstat->iobject['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix"></label>

    <div class="col-sm-9">
        <input type="hidden" name="id_object" value="{{ $lightstat->id_object }}">
        <div class="btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-success btn-sm @if($lightstat->placetype == 'port') active @endif">
                <input type="radio" name="placetype_radio" autocomplete="off" value="port"> На отдельном порту
            </label>

            <label class="btn btn-success btn-sm @if($lightstat->placetype == 'usensor') active @endif">
                <input type="radio" name="placetype_radio" autocomplete="off" value="usensor"> В составе унив. датчика
            </label>

            <input type="hidden" id="placetype" name="placetype" value="{{$lightstat->placetype}}">
        </div>
    </div>
</div>

<div class="col-sm-12 pr-0 mt-4" id="single_port_div" @if(($lightstat->placetype != 'port') && ($lightstat->placetype != '1wbus') ) style="display: none;" @endif>
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId), false, false, [], null) }}

    {{ Form::bs_autoselect('port_SDA', 'Порт SDA:', $portsSDA, old('port_SDA', is_null($port_SDA) ? 0 : $port_SDA), false, false, [], null) }}

    {{ Form::bs_autoselect('port_SCL', 'Порт SCL:', $portsSCL, old('port_SCL', is_null($port_SCL) ? 0 : $port_SCL), false, false, [], null) }}
</div>


<div class="col-sm-12 pr-0 mt-4" id="usensor_div" @if($lightstat->placetype != 'usensor') style="display: none;" @endif>
    {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id', is_null($lightstat->usensor_id) ? 0 : $lightstat->usensor_id), false, false, [], null) }}
</div>

@include('messages.two')
