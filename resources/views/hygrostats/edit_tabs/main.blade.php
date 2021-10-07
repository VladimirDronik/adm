
<br>
{{ Form::bs_simple_text('ID объекта:', $hygrostat->iobject['id']) }}
{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<div class="form-group row ">

    @if(($hygrostat->iobject && $hygrostat->iobject->is_system) || !$can['devices.show-object'])
        <div class="form-group row">
            <label class="control-label text-right col-md-3 label-fix" for="">
                Объект термостата:
            </label>
            <div class="col-md-9">
                <div class="mt-2">
                    <a class="a-color" href="{{ route('objects.edit', [$hygrostat->id_object]) }}">
                        {{ $hygrostat->iobject->name }} @if($hygrostat->iobject && $hygrostat->iobject->is_system) (системный) @endif </a>
                </div>

                <input type="hidden" name="id_object" value="{{ $hygrostat->id_object }}">
                @else
                    {{ Form::bs_autoselect_and_btn('id_object', 'Объект гигростата*:', $objects, old('id_object', $hygrostat->id_object),
                    false, false, ['required' => true]) }}
                @endif


                <div class="row" id="auto_object_div">

                    <div class="col-sm-12 pr-0 mt-4">
                        <div class="btn-group-toggle" data-toggle="buttons">

                            <label class="btn btn-success btn-sm @if($hygrostat->placetype == 'usensor') active @endif">
                                <input type="radio" name="placetype_radio" autocomplete="off" value="usensor">  На унив. датчике
                            </label>

                            <label class="btn btn-success btn-sm @if($hygrostat->placetype == 'Hite-pro') active @endif">
                                <input type="radio" name="placetype_radio" autocomplete="off" value="device">  Отдельное устройство
                            </label>

                            <input type="hidden" id="placetype" name="placetype" value="{{$hygrostat->placetype}}">

                        </div>
                    </div>


                    <div class="col-sm-12 pr-0 mt-4" id="usensor_div"  @if($hygrostat->placetype != 'usensor') style="display: none;" @endif>
                        {{ Form::bs_autoselect('usensor_id', 'Универсальный датчик:', $usensors, old('usensor_id', is_null($hygrostat->usensor_id) ? 0 : $hygrostat->usensor_id),
                        false, false, [], null) }}
                    </div>

                    <div class="col-sm-12 pr-0 mt-4" id="device_div" @if($hygrostat->placetype != 'Hite-pro') style="display: none;" @endif>
                        {{ Form::bs_autoselect('HPController_id', 'Контроллер:', $HPControllers, old('HPController_id', is_null($id_controller) ? 0 : $id_controller),
                        false, false, [], null) }}

                        {{ Form::bs_autoselect('subdev_id', 'Термометр:', $subdevs, old('subdev_id', is_null($hygrostat->subdev_id) ? 0 : $hygrostat->subdev_id),
                        false, false, [], null) }}
                    </div>

                </div>
            </div>
        </div>


</div>
@include('messages.two')