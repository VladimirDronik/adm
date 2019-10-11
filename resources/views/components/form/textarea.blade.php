<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    {!! Form::bs_label($name, $label, isset($attributes['required']), $col) !!}
    <div class="col-md-{{ 12 - $col }}">
        {{ Form::textarea($name, $value, array_merge(['class' => 'form-control', 'autocomplete' => 'off', 'style' => 'height:auto !important;'], $attributes)) }}
        {{ Form::bs_field_error($name) }}
        {{ Form::bs_field_help($help) }}
    </div>
</div>
