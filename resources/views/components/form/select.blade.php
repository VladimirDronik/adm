<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    {!! Form::bs_label($name, $label, isset($attributes['required']), $col) !!}
    <div class="col-md-{{ 12 - $col }}">
        {{ Form::select($name, $values, $selected, array_merge(['class' => 'form-control custom-select'], $attributes)) }}
        @if($errors->has($name))
            <small class="form-text text-danger m-b-none">{{ $errors->first($name) }}</small>
        @endif
        @if(!is_null($help))
            <small class="form-control-feedback">{{ $help }}</small>
        @endif
    </div>
</div>



