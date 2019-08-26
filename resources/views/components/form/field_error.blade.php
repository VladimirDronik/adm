@if(!is_null($name) && $errors->has($name))
    <small class="form-text text-danger m-b-none">{{ $errors->first($name) }}</small>
@elseif(is_null($name) && !is_null($error))
    <small class="form-text text-danger m-b-none">{{ $error }}</small>
@endif