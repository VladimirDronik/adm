<br>


{{ Form::bs_radio('type', 'Тип*:', $types, old('type', -1), ['required' => true]) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}



<div class="form-group row ">


            <div class="col-sm-11 pr-0 mt-4">
                {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'),
                   false, false, [], null) }}

                <div id='port_id_div' style="display: block">
                    {{ Form::bs_autoselect('port_id_open', 'Порт на открытие:', [], old('port_id_open'),
                        false, false, [], null) }}

                    <div id="portclosediv">
                        {{ Form::bs_autoselect('port_id_close', 'Порт на закрытие:', [], old('port_id_close'),
                           false, false, [], null) }}
                    </div>
                </div>

                <div id='hitepro_devices_div' style="display: none">
                    {{ Form::bs_autoselect('hitepro_device_open', 'Устройство на открытие:', [], old('hitepro_device_open'),
                        false, false, [], null) }}

                    <div id="deviceclosediv">
                        {{ Form::bs_autoselect('hitepro_device_close', 'Устройство на закрытие:', [], old('hitepro_device_close'),
                            false, false, [], null) }}
                    </div>
                </div>

                <input type="hidden" name="place" id="place">
            </div>



</div>

<div id="timediv" style="display: none">
    {{ Form::bs_text('time', 'Время открытия в секундах:', null) }}
</div>


