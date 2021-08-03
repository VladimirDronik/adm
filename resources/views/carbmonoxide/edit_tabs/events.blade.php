<br>
{{ Form::bs_autoselect('low_object', 'Объект влияния:', $objects, old('low_object', $carbmonoxide->low_object),
                      false, false, [],  null, 'Объект, у которого меняем состояние при достижении нижнего порога') }}

{{ Form::bs_autoselect('low_method', 'Метод объекта:', $low_methods, old('low_method',  $carbmonoxide->low_method),
    false, false, [], null, 'Метод объекта влияния при достижении нижнего порога') }}


<div class="form-group row" id="low_method_params_div"
     @if(is_null($carbmonoxide->low_method_params) && !old('low_method')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="low_method_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="low_method_params_label" for="low_method_params">
                {{ optional($carbmonoxide->emethod_low)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="low_method_params" name="low_method_params"
                       type="text" value="{{ old('low_method_params', $carbmonoxide->low_method_params) }}">
            </div>
        </div>
    </div>
</div>


<div style="height: 60px;">&nbsp;</div>



{{ Form::bs_autoselect('high_object', 'Объект влияния:', $objects, old('high_object', $carbmonoxide->high_object),
 false, false, [],  null, 'Объект, у которого меняем состояние при достищении верхнего порога') }}


{{ Form::bs_autoselect('high_method', 'Метод объекта:', $high_methods, old('high_method', $carbmonoxide->high_method),
    false, false, [], null, 'Метод объекта влияния при достижении верхнего порога') }}


<div class="form-group row" id="high_method_params_div"
     @if(is_null($carbmonoxide->high_method_params) && !old('high_method')) style="display: none;" @endif>
    <label class="control-label text-right col-md-3 pl-0 pr-0 label-fix" for="high_method_params"></label>
    <div class="col-md-9 pr-0">
        <div class="form-group row ">
            <label class="control-label text-right col-md-6 label-fix" id="high_method_params_label" for="high_method_params">
                {{ optional($carbmonoxide->emethod_high)->params }}*:</label>
            <div class="col-md-6">
                <input class="form-control" autocomplete="off" id="high_method_params" name="high_method_params"
                       type="text" value="{{ old('high_method_params', $carbmonoxide->high_method_params) }}">
            </div>
        </div>
    </div>
</div>


<div style="height: 60px;">&nbsp;</div>

<br>

