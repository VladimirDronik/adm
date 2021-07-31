
<br>
{{ Form::bs_simple_text('ID объекта:', $virtual->object['id']) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

@if(($virtual->object && $virtual->object->is_system) || !$can['devices.show-object'])
    <div class="form-group row">
        <label class="control-label text-right col-md-3 label-fix" for="">
            Объект:     </label>
        <div class="col-md-9">
            <div class="mt-2">
                <a class="a-color" href="{{ route('objects.edit', [$virtual->id_object]) }}">
                    {{ $virtual->object->name }}
                    @if($virtual->object && $virtual->object->is_system) (системный) @endif</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="id_object" value="{{ $virtual->id_object }}">
@else
    {{ Form::bs_autoselect_and_btn('id_object', 'Объект*:', $objects, old('id_object', $virtual->id_object), false, false, ['required' => true]) }}
@endif

    @include('messages.two')