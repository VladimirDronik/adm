<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    {!! Form::bs_label($name, $label, isset($attributes['required']), $col) !!}
    <div class="col-sm-{{ 12 - $col }}">
        <select autocomplete="off" @if(!$multiple) id="{{ 'auto_sel_'.$name }}" @else id="{{ 'auto_sel_'.$multiple_id }}" @endif @if(isset($attributes['required'])) required @endif data-placeholder="не выбрано" name="{{ $name }}" class="chosen-select form-control" @if($multiple) multiple @endif style="width:350px;">
            @if(!$multiple)<option value="">Не выбрано</option>@endif
            @foreach ($values as $key => $value)
                @if($multiple && !is_null($selected))
                    <option value="{{ $key }}" @if(in_array($key,$selected)) selected @endif>
                        @if($show_id) {{ $key }} - @endif {{ $value }}
                    </option>
                @else
                    <option value="{{ $key }}" @if($key == $selected) selected @endif>
                        @if($show_id) {{ $key }} - @endif {{ $value }}
                    </option>
                @endif
            @endforeach
        </select>
        {{ Form::bs_field_error($name) }}
        {{ Form::bs_field_help($help) }}
    </div>
</div>
