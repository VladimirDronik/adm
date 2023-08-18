
<br>
{{ Form::bs_simple_text('ID объекта:', $termostat->iobject['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<div class="form-group row ">

        <div class="form-group row">
            @if(($termostat->iobject && $termostat->iobject->is_system) || !$can['devices.show-object'])
            <label class="control-label text-right col-md-3 label-fix" for="">
                Объект термостата:
            </label>
            @endif
            <div class="col-md-9">
                @if(($termostat->iobject && $termostat->iobject->is_system) || !$can['devices.show-object'])
                <div class="mt-2">
                    <a class="a-color" href="{{ route('objects.edit', [$termostat->id_object]) }}">
                        {{ $termostat->iobject->name }} @if($termostat->iobject && $termostat->iobject->is_system) (системный) @endif </a>
                </div>

                <input type="hidden" name="id_object" value="{{ $termostat->id_object }}">
                @else
                    {{ Form::bs_autoselect_and_btn('id_object', 'Объект термостата*:', $objects, old('id_object', $termostat->id_object),
                    false, false, ['required' => true]) }}
                @endif


                <div class="row" id="auto_object_div">

                    <div class="col-sm-12 pr-0 mt-4">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-success btn-sm @if($termostat->placetype == 'port') active @endif">
                                <input type="radio" name="placetype_radio" autocomplete="off"  value="port"> На порту
                            </label>

                            <label class="btn btn-success btn-sm @if($termostat->placetype == '1wbus') active @endif">
                                <input type="radio" name="placetype_radio" autocomplete="off"  value="1wbus" >  На шине
                            </label>

                            <label class="btn btn-success btn-sm @if($termostat->placetype == 'usensor') active @endif">
                                <input type="radio" name="placetype_radio" autocomplete="off" value="usensor">  На унив. датчике
                            </label>

                            <input type="hidden" id="placetype" name="placetype" value="{{$termostat->placetype}}">
                        </div>
                    </div>

                    <div class="col-sm-12 pr-0 mt-4" id="single_port_div" @if(($termostat->placetype != 'port') && ($termostat->placetype != '1wbus') )  style="display: none;" @endif>
                        {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', is_null($deviceId) ? 0 : $deviceId),
                        false, false, [], null) }}

                        {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', is_null($portId) ? 0 : $portId),
                        false, false, [], null) }}

                    </div>

                    <div class="col-sm-12 pr-0 mt-4" id="1wbus_port_div"  @if($termostat->placetype != '1wbus') style="display: none;" @endif>
                        {{ Form::bs_text('id_termometr', 'Код:', null, [], 'Уникальный ID термодатчика. Например, ff750c311703') }}
                    </div>

                    <div class="col-sm-12 pr-0 mt-4" id="usensor_div"  @if($termostat->placetype != 'usensor') style="display: none;" @endif>
                        {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id', is_null($termostat->usensor_id) ? 0 : $termostat->usensor_id),
                        false, false, [], null) }}
                    </div>
                </div>
            </div>
        </div>

        @include('messages.two')
</div>