@if($systemMethods->isNotEmpty())
    {{ Form::bs_title('Системные методы') }}

    <div class="form-group row">
        @can('superadmin')
            <div class="col-md-1"><i>ID</i></div>
        @endcan
        <label class="col-md-4"><i>Название метода</i></label>
        @if($device->gateway_type == \App\Models\HomeObject::GATEWAY_MODBUS)
            <div class="col-md-2 text-left"><i>Устройство</i></div>
            <div class="col-md-5 text-left"><i>Значение</i></div>
        @endif
    </div>
    <div id="system_methods_div">
        @foreach($systemMethods as $method)
            @if($method->is_system)
                <div class="form-group row">
                    @can('superadmin')
                        <div class="col-md-1">
                            {{ $method->id }}
                        </div>
                    @endcan
                    <label class="col-md-3" >
                        {{$method->name}}
                        @if($method->is_need_param)
                            <i class="fa fa-asterisk f-s-10 text-muted" title="Метод с параметром"></i>
                        @endif
                    </label>
                    @if($method->comment)
                        <div class="col-md-1">
                            <img src="{{ asset('ela/images/info.png') }}" width="23" height="23" title="{{ $method->comment }}"></img>
                        </div>
                    @endif
                    @if($device->gateway_type == \App\Models\HomeObject::GATEWAY_MODBUS)
                        <div class="col-md-2">
                            <select autocomplete="off" id="{{ 'auto_sel_slaver_id_'.$method->id }}" required data-placeholder="не выбрано" name="{{ 'slaver_id_'.$method->id }}" class="chosen-select form-control">
                                @foreach ($modbusSlavers as $id => $name)
                                    <option value="{{ $id }}" @if($id == old('slaver_id_'.$method->id, $method->register ? $method->register->slaver->id : $device->gateway_id)) selected @endif>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <select autocomplete="off" id="{{ 'auto_sel_register_id_'.$method->id }}" data-placeholder="не выбрано" name="{{ 'register_id_'.$method->id }}" class="chosen-select form-control"></select>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
@endif

{{ Form::bs_title('Пользовательские методы') }}
<div class="form-group row">
    @can('superadmin')
        <div class="col-md-1"><i>ID</i></div>
    @endcan
    <label class="col-md-4"><i>Название метода</i></label>
    <div class="col-md-3"><i>Простое действие</i></div>
    <div class="col-md-2"><i>Скрипт</i></div>
    <div class="col-md-2 text-right"></div>
</div>
<div id="methods_div">
    @foreach($device->object->methods as $method)
        @if(!$method->is_system)
            <div class="form-group row" id="div{{$method->id}}">
                @can('superadmin')
                    <label class="col-md-1" id="methodid{{$method->id}}">
                        {{$method->id}}
                    </label>
                @endcan
                <label class="col-md-3" id="name{{$method->id}}">
                    {{$method->name}}
                </label>
                @if($method->comment)
                    <div class="col-md-1" id="comment{{$method->id}}">
                        <img src="{{ asset('ela/images/info.png') }}" width="23" height="23" title="{{ $method->comment }}"></img>
                    </div>
                @endif
                <div class="col-md-3" id="easy{{$method->id}}">
                    {{ $method->easy }}
                </div>
                <div class="col-md-2" id="script{{$method->id}}">
                    {{ optional($method->escript)->name }}
                </div>
                <div class="col-md-2 text-right">
                    @if(!$method->is_system)
                        <button type="button" data-id="{{ $method->id }}"
                                data-type="{{ $method->type }}"
                                data-script-id="{{ $method->script }}"
                                data-device="{{ $method->device_id }}"
                                data-port="{{ $method->port }}"
                                data-action="{{ $method->action }}"
                                class="btn btn-info btn-sm btn-rounded edit_btn">
                            <i class="fa fa-cog fa-lg"></i>
                        </button>
                        <button type="button" data-id="{{ $method->id }}" data-name="{{ $method->name }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    @endforeach
</div>
<div class="form-group row">
    <div class="col-md-12 text-left">
        <button id="add_btn" type="button" class="btn btn-primary">
            <i class="fa fa-plus fa-lg"></i> Добавить метод
        </button>
    </div>
</div>
