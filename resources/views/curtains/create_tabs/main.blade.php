<br>
{{ Form::bs_radio('place', 'Тип управления*:', $places, old('place'), ['required' => true]) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}
{{ Form::bs_autoselect('vendor', 'Производитель привода*:', $vendors, old('vendor'), false, false, [], null) }}

<div class="form-group row ">
    <div class="col-sm-11 pr-0 mt-4">
        <div id='device_id_div' hidden>
            {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id'), false, false, [], null) }}
        </div>

        <div id='port_id_div' hidden>
            {{ Form::bs_autoselect('port_id_open', 'Порт на открытие:', [], old('port_id_open'), false, false, [], null) }}

            {{ Form::bs_autoselect('port_id_close', 'Порт на закрытие:', [], old('port_id_close'), false, false, [], null) }}
        </div>

        <div id='bus_id_div' hidden>
            {{ Form::bs_radio('type', 'Тип привода:', $types, old('type'), []) }}

            {{ Form::bs_autoselect('bus_id', 'Шина:', $buses, old('bus_id'), false, false, [], null) }}
        </div>

        <div id='rs_485_div' hidden>
            {{ Form::bs_text('address', 'Адрес:', old('address'), [], 'От 0 до 255') }}

            {{ Form::bs_text('group', 'Группа:', old('group'), [], 'От 0 до 255') }}

            {{ Form::bs_checkbox('is_inverse', 'Инвертировать проценты:', old('is_inverse')) }}
        </div>

        <div id='phase_time_div' hidden>
            {{ Form::bs_text('time', 'Время открытия или закрытия*:', old('time'), ['required' => true], 'В секундах') }}
        </div>
    </div>
</div>



