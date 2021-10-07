
<br>
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}


<div class="form-group row ">
    <label class="control-label text-right col-md-3 label-fix" for="id_object">
        <strong>Объект гигростата*:</strong>
    </label>
    <div class="col-sm-9">
        <div class="form-group row">
            <div class="col-md-12 p-0">
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-success btn-sm active">
                        <input type="radio" name="object_type" autocomplete="off" checked value="auto"> Создать автоматически
                    </label>
                    @can('devices.create-manual-object')
                        <label class="btn btn-success btn-sm">
                            <input type="radio" name="object_type" autocomplete="off"  value="manual">  Выбор из списка
                        </label>
                    @endcan
                </div>
            </div>
        </div>
        <div class="row" id="manual_object_div" style="display: none;">
            <div class="col-sm-11 pr-0">
                <select autocomplete="off" id="auto_sel_id_object"
                        data-placeholder="не выбрано"
                        name="id_object"
                        class="chosen-select form-control"
                        style="width:350px;">
                    <option value="">Не выбрано</option>
                    @foreach ($objects as $key => $value)
                        <option value="{{ $key }}" @if($key == old('id_object')) selected @endif>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-1 pt-1 text-left">
                <button type="button" id="auto_sel_btn_id_object" class="btn btn-default btn-sm" title=" Создать объект ">
                    <i class="fa fa-plus"></i></button>
            </div>
        </div>


        <div class="row" id="auto_object_div">
            <div class="col-sm-12 pr-0">
                <p>
                    При создании гигростата будет создан объект с таким же названием.
                    У объекта будет создан метод «Проверка гигростата».
                    К методу будет привязан системный скрипт «Проверка гигростата»
                    (если такого скрипта нет, то он будет создан) и
                    будет создано событие «Проверка гигростата» (каждые 5 мин).
                </p>
            </div>
            <div class="col-sm-12 pr-0  mt-4">
                <div class="btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-success btn-sm active">
                        <input type="radio" name="placetype_radio" autocomplete="off" value="usensor">  На унив. датчике
                    </label>

                    <label class="btn btn-success btn-sm">
                        <input type="radio" name="placetype_radio" autocomplete="off" value="device">  Отдельное устройство
                    </label>

                    <input type="hidden" id="placetype" name="placetype" value="usensor">

                </div>
            </div>



            <div class="col-sm-12 pr-0 mt-4" id="usensor_div"  style="display: block;">
                {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id'),
                   false, false, [], null) }}
            </div>

            <div class="col-sm-12 pr-0 mt-4" id="device_div"  style="display: none";>
                {{ Form::bs_autoselect('HPController_id', 'Контроллер:', $HPControllers, old('HPController_id'),
                   false, false, [], null) }}

                {{ Form::bs_autoselect('subdev_id', 'Гигростат:', [], old('subdev_id'),
                    false, false, [], null) }}
            </div>

        </div>

    </div>
</div>