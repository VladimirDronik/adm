@if($lamp->object && $lamp->object->is_system && $systemMethods->isNotEmpty())
    {{ Form::bs_title('Системные методы') }}

    <div class="form-group row">
        @can('superadmin')
            <div class="col-md-1"><i>ID</i></div>
        @endcan
        <label class="col-md-3"><i>Название метода</i></label>
        <div class="col-md-4 text-left"><i>Комментарий</i></div>
        @if($lamp->gateway_type == \App\Models\HomeObject::GATEWAY_MODBUS)
            <div class="col-md-2 text-left"><i>Устройство</i></div>
            <div class="col-md-2 text-left"><i>Значение</i></div>
        @endif
    </div>
    <div id="system_methods_div">
        @foreach($systemMethods as $method)
            @if($method->is_system)
                <div class="form-group row" id="div{{$method->id}}">
                    @can('superadmin')
                        <div class="col-md-1" id="methodid{{$method->id}}">
                            {{ $method->id }}
                        </div>
                    @endcan
                    <label class="col-md-3" id="name{{$method->id}}">
                        {{$method->name}}
                        @if($method->is_need_param)
                            <i class="fa fa-asterisk f-s-10 text-muted" title="Метод с параметром"></i>
                        @endif
                    </label>
                    <div class="col-md-4" id="comment{{$method->id}}">
                        {{ $method->comment }}
                    </div>
                    @if($lamp->gateway_type == \App\Models\HomeObject::GATEWAY_MODBUS)
                        <div class="col-md-2">
                            <select autocomplete="off" id="{{ 'auto_sel_slaver_id_'.$method->id }}" required data-placeholder="не выбрано" name="{{ 'slaver_id_'.$method->id }}" class="chosen-select form-control">
                                @foreach ($modbusSlavers as $id => $name)
                                    <option value="{{ $id }}" @if($id == old('slaver_id_'.$method->id, $method->register ? $method->register->slaver->id : $lamp->gateway_id)) selected @endif>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select autocomplete="off" id="{{ 'auto_sel_register_id_'.$method->id }}" data-placeholder="не выбрано" name="{{ 'register_id_'.$method->id }}" class="chosen-select form-control"></select>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@endif