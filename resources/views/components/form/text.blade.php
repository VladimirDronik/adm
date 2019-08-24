<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    @if(isset($attributes['required']))
        {!! Form::strongLabel($label, $name) !!}
    @else
        {{ Form::label($name, $label, ['class' => 'col-sm-3 col-form-label']) }}
    @endif
    <div class="col-sm-9">
        {{ Form::text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
        @if ($errors->has($name))
            <span class="form-text text-danger m-b-none">{{ $errors->first($name) }}</span>
        @endif
        @if (!is_null($help))
            <span class="form-text m-b-none">{!! $help !!}</span>
        @endif
    </div>
</div>

