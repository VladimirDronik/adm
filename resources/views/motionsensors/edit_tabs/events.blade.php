<br>
<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие при нормальном режиме</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('object_normal', 'Объект:', $objects,  old('object_normal', is_null($object_normal) ? 0 : $object_normal),
  false, false, [],  null, 'Объект, методы которого интересуют') }}

        {{ Form::bs_autoselect('method_normal', 'Метод:', $methods_normal, old('method_normal', is_null($motionsensor->method_normal) ? 0 : $motionsensor->method_normal),
    false, false, [], null, 'Метод, который вызывается при срабатывании датчика в нормальном режиме') }}
    </div>
</div>

<div class="form-group row" id="method_normal_params_div"
     @if(is_null($motionsensor->method_normal_params) && !old('method_normal')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_normal_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_normal_params_label" for="method_normal_params">
                {{ optional($motionsensor->emethod_normal)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_normal_params" name="method_normal_params"
                       type="text" value="{{ old('method_normal_params', $motionsensor->method_normal_params) }}">
            </div>
        </div>
    </div>
</div>




<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие при эко режиме</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('object_eco', 'Объект:', $objects,  old('object_eco', is_null($object_eco) ? 0 : $object_eco),
  false, false, [],  null, 'Объект, методы которого интересуют') }}

        {{ Form::bs_autoselect('method_eco', 'Метод:', $methods_eco, old('method_eco', is_null($motionsensor->method_eco) ? 0 : $motionsensor->method_eco),
    false, false, [], null, 'Метод, который вызывается при срабатывании датчика в эко режиме') }}

    </div>
</div>

<div class="form-group row" id="method_eco_params_div"
     @if(is_null($motionsensor->method_eco_params) && !old('method_eco')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_eco_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_eco_params_label" for="method_eco_params">
                {{ optional($motionsensor->emethod_eco)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_eco_params" name="method_eco_params"
                       type="text" value="{{ old('method_eco_params', $motionsensor->method_eco_params) }}">
            </div>
        </div>
    </div>
</div>


<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие при ночном режиме</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('object_night', 'Объект:', $objects, old('object_night', is_null($object_night) ? 0 : $object_night),
  false, false, [],  null, 'Объект, методы которого интересуют') }}

        {{ Form::bs_autoselect('method_night', 'Метод:',  $methods_night, old('method_night', is_null($motionsensor->method_night) ? 0 : $motionsensor->method_night),
    false, false, [], null, 'Метод, который вызывается при срабатывании датчика в ночном режиме') }}

    </div>
</div>

<div class="form-group row" id="method_night_params_div"
     @if(is_null($motionsensor->method_night_params) && !old('method_night')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_night_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_night_params_label" for="method_night_params">
                {{ optional($motionsensor->emethod_night)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_night_params" name="method_night_params"
                       type="text" value="{{ old('method_night_params', $motionsensor->method_night_params) }}">
            </div>
        </div>
    </div>
</div>



<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие при утреннем режиме</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('object_morning', 'Объект:', $objects, old('object_morning', is_null($object_morning) ? 0 : $object_morning),
  false, false, [],  null, 'Объект, методы которого интересуют') }}


        {{ Form::bs_autoselect('method_morning', 'Метод:', $methods_morning, old('method_morning', is_null($motionsensor->method_morning) ? 0 : $motionsensor->method_morning),
            false, false, [], null, 'Метод, который вызывается при срабатывании датчика в утреннем режиме (сумерки)') }}

    </div>
</div>


<div class="form-group row" id="method_morning_params_div"
     @if(is_null($motionsensor->method_morning_params) && !old('method_morning')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_morning_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_morning_params_label" for="method_morning_params">
                {{ optional($motionsensor->emethod_morning)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_morning_params" name="method_morning_params"
                       type="text" value="{{ old('method_morning_params', $motionsensor->method_morning_params) }}">
            </div>
        </div>
    </div>
</div>



<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие при вечернем режиме</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('object_evening', 'Объект:', $objects, old('object_evening', is_null($object_evening) ? 0 : $object_evening),
  false, false, [],  null, 'Объект, методы которого интересуют') }}


        {{ Form::bs_autoselect('method_evening', 'Метод:', $methods_evening, old('method_evening', is_null($motionsensor->method_evening) ? 0 : $motionsensor->method_evening),
   false, false, [], null, 'Метод, который вызывается при срабатывании датчика в вечернем режиме (сумерки)') }}

    </div>
</div>

<div class="form-group row" id="method_evening_params_div"
     @if(is_null($motionsensor->method_evening_params) && !old('method_evening')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_evening_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_evening_params_label" for="method_evening_params">
                {{ optional($motionsensor->emethod_evening)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_evening_params" name="method_evening_params"
                       type="text" value="{{ old('method_evening_params', $motionsensor->method_evening_params) }}">
            </div>
        </div>
    </div>
</div>

<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие в режиме охраны</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('object_guard', 'Объект:', $objects, old('object_guard', is_null($object_guard) ? 0 : $object_guard),
  false, false, [],  null, 'Объект, методы которого интересуют') }}


        {{ Form::bs_autoselect('method_guard', 'Метод:', $methods_guard, old('method_guard', is_null($motionsensor->method_guard) ? 0 : $motionsensor->method_guard),
   false, false, [], null, 'Метод, который вызывается при срабатывании датчика в режиме охраны') }}

    </div>
</div>

<div class="form-group row" id="method_guard_params_div"
     @if(is_null($motionsensor->method_guard_params) && !old('method_guard')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_guard_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_guard_params_label" for="method_guard_params">
                {{ optional($motionsensor->emethod_guard)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_guard_params" name="method_guard_params"
                       type="text" value="{{ old('method_guard_params', $motionsensor->method_guard_params) }}">
            </div>
        </div>
    </div>
</div>




<div class="row" id="select_methods_div">

    <div class="col-sm-11 pr-0">
        <div style="height: 10px;">&nbsp;</div>
        <hr>
        <h4>Действие при пороговом значении светостата</h4>
        <div style="height: 20px;">&nbsp;</div>
    </div>

    <div class="col-sm-12 pr-0 mt-4">

        {{ Form::bs_autoselect('lightstat', 'Светостат:', $lightstats, old('lightstat', is_null($motionsensor->lightstat) ? 0 : $motionsensor->lightstat),
  false, false, [],  null, 'Светостат, значение которого будем проверять') }}

        {{ Form::bs_radio('equality', 'Если значение светостата:', $equality, old('equality', $motionsensor->equality), ['required' => true]) }}

        {{ Form::bs_text('lightvalue', 'Значение освещенности:', old('lightvalue', $motionsensor->lightvalue)) }}

        {{ Form::bs_autoselect('object_light', 'Объект:', $objects, old('object_guard', is_null($object_light) ? 0 : $object_light),
  false, false, [],  null, 'Объект, методы которого интересуют') }}

        {{ Form::bs_autoselect('method_light', 'Метод:', $methods_light, old('method_guard', is_null($motionsensor->method_light) ? 0 : $motionsensor->method_light),
   false, false, [], null, 'Метод, который вызывается при пороговом значнии светостата') }}

    </div>


    <div class="form-group row" id="method_light_params_div"
         @if(is_null($motionsensor->method_light_params) && !old('method_light')) style="display: none;" @endif>
        <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_light_params"></label>
        <div class="col-md-9 pr-0">
            <div class="form-group row ">
                <label class="control-label text-right col-md-6 label-fix" id="method_light_params_label" for="method_light_params">
                    {{ optional($motionsensor->emethod_light)->params }}*:</label>
                <div class="col-md-6">
                    <input class="form-control" autocomplete="off" id="method_light_params" name="method_light_params"
                           type="text" value="{{ old('method_light_params', $motionsensor->method_light_params) }}">
                </div>
            </div>
        </div>
    </div>


</div>