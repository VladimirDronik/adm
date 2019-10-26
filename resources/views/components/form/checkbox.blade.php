<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    {!! Form::bs_label($name, $label, isset($attributes['required']), $col) !!}
    <div class="col-md-{{ 12 - $col }}">
        {{ Form::checkbox($name, 1, $is_checked ? true : null, array_merge(['class' => 'mt-2', 'style' => 'cursor:pointer;', 'autocomplete' => 'off'], $attributes)) }}
        {{ Form::bs_field_error($name) }}
        {{ Form::bs_field_help($help) }}
    </div>
</div>
