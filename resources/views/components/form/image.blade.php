<div class="form-group row ">
    <label class="control-label text-right col-md-{{$col}} label-fix" for="">
        {{ $label }}
    </label>
    <div class="col-md-{{12-$col}}">
        <p class="p-t-6">
            <img src="{{ asset($value) }}"
                 width="40" height="40" id="img_{{$prefix}}" style="background: gray;">
            <button type="button" class="btn btn-default pull-right img_btn"
                    data-toggle="modal" data-target="#img_modal" onclick="changeViewImage('{{$prefix}}')"> Выбрать</button>
        </p>
    </div>
</div>
{{ Form::bs_hidden($prefix.'_image', $value) }}
