
<br>

<input type="checkbox" name="is_dimer" @if($lamp->type == \App\Models\Lamp::TYPE_DIMER) checked @endif > Диммируется
<br><br>
<div id='dimer_fields_div' hidden>
    {{ Form::bs_number('value', 'Значение*:', old('value',  $lamp->value), ['required' => true, 'max' => 127]) }}

    {{ Form::bs_number('speed', 'Скорость*:', old('speed', $lamp->speed), ['required' => true, 'min' => 0, 'max' => 127]) }}
</div>
<br><br>

<input type="checkbox" id="alice_checkbox" name="alice_checkbox" @if  ($alice['active'] == 1) checked @endif > Управлять лампой через Алису
<br><br>
<div id="div_command"  @if  ($alice['active'] == 0) style="display: none" @endif >
    {{ Form::bs_text('alice_command', 'Название*:', $alice['name']) }}

    {{ Form::bs_autoselect('room', 'Помещение*:',  $rooms,
    is_null($alice['room']) ? 0 : $alice['room']) }}
</div>

