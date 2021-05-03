
<br>
<input type="checkbox" id="alice_checkbox" name="alice_checkbox" @if  ($alice['active'] == 1) checked @endif > Управлять реле через Алису
<br><br>
<div id="div_command"  @if  ($alice['active'] == 0) style="display: none" @endif >
    {{ Form::bs_text('alice_command', 'Название*:', $alice['name']) }}

    {{ Form::bs_autoselect('room', 'Помещение*:',  $rooms,
    is_null($alice['room']) ? 0 : $alice['room']) }}
</div>

