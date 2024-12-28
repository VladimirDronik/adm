{{ Form::bs_text('name', 'Название*:', old('name', $daliDevice->name), ['required' => true]) }}

@if(!$daliDevice->is_group)
    {{ Form::bs_autoselect('room', 'Размещение:', $rooms, old('room', $daliDevice->room), false, false, []) }}
@endif

{{ Form::bs_simple_text('Адрес:', ($daliDevice->is_group ? 'G' : 'A') . $daliDevice->address) }}

{{ Form::bs_simple_text('Шлюз:', $daliDevice->modbusSlaver->name) }}

@if(!$daliDevice->is_group)
    {{ Form::bs_simple_text('Неисправность:', $daliDevice->failure ? 'Да' : 'Нет') }}
@endif

@if($daliDevice->object)
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">Состояние:</label>
        <div class="col-md-9">
            <button type="button" class="btn btn-success m-b-10 m-l-5" id="switchStatus">{{ $daliDevice->object->status }}</button>
        </div>
    </div>

    <div class="form-group row ">
        <label class="control-label text-right col-md-3 label-fix" for="brightness">Яркость:</label>
        <div class="col-md-9">
            <input class="form-control" style="width: 30%; display: inline-block;" autocomplete="off" name="brightness" type="number" min="0" max="100" value="{{ old('brightness', $daliDevice->brightness) }}">
            <button type="button" class="btn btn-success m-b-10 m-l-5" id="setBrightness">Применить</button>
        </div>
    </div>

    @if($daliDevice->is_cct)
        <div class="form-group row ">
            <label class="control-label text-right col-md-3 label-fix" for="cct">Цветовая температура:</label>
            <div class="col-md-9">
                <input class="form-control" style="width: 30%; display: inline-block;" autocomplete="off" name="cct" type="number" min="1000" max="10000" value="{{ old('cct', $daliDevice->cct) }}">
                <button type="button" class="btn btn-success m-b-10 m-l-5" id="setCct">Применить</button>
            </div>
        </div>
    @endif
@else
    {{ Form::bs_simple_text('Яркость:', $daliDevice->brightness) }}

    @if($daliDevice->is_cct)
        {{ Form::bs_simple_text('Цветовая температура:', $daliDevice->cct) }}
    @endif
@endif

@if($daliDevice->is_group)
    <hr>
    <br>
    <h4>Устройства в группе:</h4>
    @if($daliDevice->daliDevices->isNotEmpty())
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50%;">Название</th>
                    <th style="width: 40%;">Адрес</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($daliDevice->daliDevices as $relatedDaliDevice)
                <tr id="tr{{$relatedDaliDevice->id}}">
                    <td>
                        <a href="{{ route('mod_bus.dali_devices.edit', [$relatedDaliDevice->id]) }}">{{ $relatedDaliDevice->name }}</a>
                    </td>
                    <td>
                        A{{ $relatedDaliDevice->address }}
                    </td>
                    <td align="center">
                        <a href="{{ route('mod_bus.dali_devices.edit', [$relatedDaliDevice->id]) }}" class="btn btn-info btn-sm btn-rounded">
                            <i class="fa fa-cog fa-lg"></i>
                        </a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn" data-id_object="{{ $relatedDaliDevice->id_object }}">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    <br>
    <div class="form-group row {{ $errors->has('dali_device_object_id') ? ' has-error' : '' }}">
        <label class="control-label text-right col-md-3 label-fix" for="dali_device_object_id">Новое устройство:</label>
        <div class="col-sm-9">
            <select autocomplete="off" id="auto_sel_dali_device_object_id" data-placeholder="не выбрано" name="dali_device_object_id" class="chosen-select form-control" style="width:350px;">
                <option value="">Не выбрано</option>
                @foreach ($daliDevices as $daliDeviceIdObject => $daliDeviceName)
                    <option value="{{ $daliDeviceIdObject }}">{{ $daliDeviceName }}</option>
                @endforeach
            </select>
            {{ Form::bs_field_error('dali_device_object_id') }}
            <button type="button" class="btn btn-success m-b-10 m-l-5" id="addDaliDevice">Добавить</button>
        </div>
    </div>
@else
    @if($daliDevice->groups->isNotEmpty())
        <hr>
        <br>
        <h4>Группы в которые входит данное устройство:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50%;">Название</th>
                    <th style="width: 40%;">Адрес</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            @foreach($daliDevice->groups as $group)
                <tr id="tr{{$group->id}}">
                    <td>
                        <a href="{{ route('mod_bus.dali_devices.edit', [$group->id]) }}">{{ $group->name }}</a>
                    </td>
                    <td>
                        G{{ $group->address }}
                    </td>
                    <td align="center">
                        <a href="{{ route('mod_bus.dali_devices.edit', [$group->id]) }}" class="btn btn-info btn-sm btn-rounded">
                            <i class="fa fa-cog fa-lg"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endif
