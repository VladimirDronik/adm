<div class="form-group row">
    {!! Form::bs_label($name, $label, false, $col) !!}
    <div class="col-md-{{ 12 - $col }}">
        <div class="mt-2">{{ $value }}</div>
        {{ Form::bs_field_help($help) }}
    </div>
</div>
