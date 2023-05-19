<br>

{{ Form::bs_title('Одиночное нажатие') }}

{{ Form::bs_autoselect('object', 'Объект:', $objects, old('object', $object),
    false, false, [], null, 'Объект, на который воздействуем') }}

{{ Form::bs_autoselect('method', 'Метод:', $methods, old('method', $method),
    false, false, [], null, 'Метод объекта при одиночном нажатии кнопки') }}

<div class="form-group row" id="method_params_div"
     {{--@if(!old('method'))  style="display: none;" @endif>--}}
     @if($params['value'] == '')  style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="method_params_label" for="method_params">{{ $params['name'] }}</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="method_params" name="method_params"
                       type="text" value="{{ $params['value'] }}">
            </div>
        </div>
    </div>
</div>

<div id="double_clk_div" @if ($hp_device!=null || $switch->type!='button') style="display: none" @endif>

    {{ Form::bs_title('Двойное нажатие') }}


    {{ Form::bs_autoselect('object_dc', 'Объект:', $objects, old('object_dc', $object_dc),
        false, false, [], null, 'Объект, на который воздействуем') }}

    {{ Form::bs_autoselect('method_dc', 'Метод:', $methods_dc, old('method_dc', $method_dc),
        false, false, [], null, 'Метод объекта при двойном нажатии кнопки') }}

    <div class="form-group row" id="method_dc_params_div"
         @if($params_dc['value'] == '')  style="display: none;" @endif>
        <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_dc_params"></label>
        <div class="col-md-9 pr-0">
            <div class="form-group row ">
                <label class="control-label text-right col-md-6 label-fix" id="method_dc_params_label" for="method_dc_params">{{ $params_dc['name'] }}</label>
                <div class="col-md-6">
                    <input class="form-control" autocomplete="off" id="method_dc_params" name="method_dc_params"
                           type="text" value="{{ $params_dc['value'] }}">
                </div>
            </div>
        </div>
    </div>
</div>


<div id="long_clk_div" @if ($hp_device!=null || $switch->type!='button') style="display: none" @endif>
    {{ Form::bs_title('Длительное нажатие') }}

    {{ Form::bs_autoselect('object_lc', 'Объект:', $objects, old('object_lc', $object_lc),
        false, false, [], null, 'Объект, на который воздействуем') }}

    {{ Form::bs_autoselect('method_lc', 'Метод:', $methods_lc, old('method_lc', $method_lc),
        false, false, [], null, 'Метод объекта при длительном нажатии кнопки') }}

    <div class="form-group row" id="method_lc_params_div"
         @if($params_lc['value'] == '')  style="display: none;" @endif>
        <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="method_lc_params"></label>
        <div class="col-md-9 pr-0">
            <div class="form-group row ">
                <label class="control-label text-right col-md-6 label-fix" id="method_lc_params_label" for="method_lc_params">{{ $params_lc['name'] }}</label>
                <div class="col-md-6">
                    <input class="form-control" autocomplete="off" id="method_lc_params" name="method_lc_params"
                           type="text" value="{{ $params_lc['value'] }}">
                </div>
            </div>
        </div>
    </div>
</div>