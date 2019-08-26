<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    {!! Form::bs_label($name, $label, isset($attributes['required']), $col) !!}
    <div class="col-md-{{ 12 - $col }}">
        <div class="btn-group-toggle" data-toggle="buttons">
            @foreach($values as $key => $value)
                <label class="btn btn-success" >
                    <input type="radio" name="{{ $name }}" autocomplete="off" value="{{ $key }}"> {{ $value }}
                </label>
            @endforeach
        </div>
        {{ Form::bs_field_error($name) }}
        {{ Form::bs_field_help($help) }}
    </div>
</div>

