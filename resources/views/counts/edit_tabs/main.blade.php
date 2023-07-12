<br>

{{ Form::bs_simple_text('ID объекта:', $count->object['id']) }}
<div class="form-group row">
    <label class="control-label text-right col-md-3 label-fix" for="">
        Тип счетчика:     </label>
    <div class="col-md-9">
        <div class="mt-2">
            <img src="{{ asset('ela/images/counts/'.$count->image) }}" title="{{ $count->rus_type }}" alt="{{ $count->rus_type }}" width="30" height="30">
            {{ $count->rus_type }}
        </div>
    </div>
</div>

{{ Form::bs_text('name', 'Название*:', null, ['required' => true]) }}

<input type="hidden" name="id_object" value="{{ $count->id_object }}">

<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('device_id', 'Контроллер:', $devices, old('device_id', $idDevice),
       false, false, [], null) }}

    {{ Form::bs_autoselect('port_id', 'Порт:', $ports, old('port_id', $idPort),
        false, false, [], null) }}
</div>

