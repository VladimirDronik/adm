
<br>
{{ Form::bs_simple_text('ID объекта:', $virtual->object['id']) }}

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<input type="hidden" name="id_object" value="{{ $virtual->id_object }}">

    @include('messages.two')