@if(is_null($class))
    <label class="control-label text-right col-md-{{$col}}" for="{{ $name }}">
        @if($is_required) <strong>{{ $label }}</strong> @else {{ $label }} @endif
    </label>
@else
    <label class="{{ $class }}" for="{{ $name }}">
        @if($is_required) <strong>{{ $label }}</strong> @else {{ $label }} @endif
    </label>
@endif