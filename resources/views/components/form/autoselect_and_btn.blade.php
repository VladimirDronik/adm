<div class="form-group row {{ $errors->has($name) ? ' has-error' : '' }}">
    {!! Form::bs_label($name, $label, isset($attributes['required']), $col) !!}
    <div class="col-sm-{{ 12 - $col }}">
        <div class="row">
            <div class="col-sm-@if($is_btn){{'11'}}@else{{ '12' }}@endif @if($is_btn) pr-0 @endif">
                <select autocomplete="off" @if(!$multiple) id="auto_sel_{{ $name }}" @else id="auto_sel_{{ $multiple_id }}" @endif
                        @if(isset($attributes['required'])) required @endif data-placeholder="не выбрано"
                        name="{{ $name }}"
                        class="chosen-select form-control"
                        @if($multiple) multiple @endif style="width:350px;">
                    @if(!$multiple)<option value="">Не выбрано</option>@endif
                    @foreach ($values as $key => $value)
                        @if($multiple && !is_null($selected))
                            <option value="{{ $key }}" @if(in_array($key,$selected)) selected @endif>
                                @if($show_id) {{ $key }} - @endif {{ $value }}
                            </option>
                        @else
                            <option value="{{ $key }}" @if($key == $selected) selected @endif>
                                @if($show_id) {{ $key }} - @endif {{ $value }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            @if($is_btn)
            <div class="col-sm-1 pt-1 text-left">
                <button type="button" id="auto_sel_btn_{{ $name }}" class="btn btn-default btn-sm" title="@if(empty($btn_title)) Создать объект @else {!! $btn_title !!}@endif">
                    @if(empty($btn_label)) <i class="fa fa-plus"></i>@else {!! $btn_label !!} @endif</button>
            </div>
            @endif
        </div>
        {{ Form::bs_field_error($name) }}
        {{ Form::bs_field_help($help) }}
    </div>
</div>
