@if($object->is_system)
    {{ Form::bs_title('Системные методы') }}

    <div class="form-group row">
        <label class="col-md-5"><i>Название метода</i></label>
        <div class="col-md-7 text-left"><i>Комментарий</i></div>
    </div>
    <div id="system_methods_div">
        @foreach($object->methods as $method)
            @if($method->is_system)
                <div class="form-group row" id="div{{$method->id}}">
                    <label class="col-md-5" id="name{{$method->id}}">
                        {{$method->name}}
                        @if($method->is_need_param)
                            <i class="fa fa-asterisk f-s-10 text-muted" title="Метод с параметром"></i>
                        @endif
                    </label>
                    <div class="col-md-7" id="comment{{$method->id}}">
                        {{ $method->comment }}
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{ Form::bs_title('Пользовательские методы') }}
@else
    {{ Form::bs_title('Методы') }}
@endif
<div class="form-group row">
    <label class="col-md-3"><i>Название метода</i></label>
    <div class="col-md-3"><i>Простое действие</i></div>
    <div class="col-md-2"><i>Скрипт</i></div>
    <div class="col-md-2"><i>Комментарий</i></div>
    <div class="col-md-2 text-right"></div>
</div>
<div id="methods_div">
    @foreach($object->methods as $method)
        @if(!$method->is_system || !$object->is_system)
            <div class="form-group row" id="div{{$method->id}}">
                <label class="col-md-3" id="name{{$method->id}}">
                    {{$method->name}}
                </label>
                <div class="col-md-3" id="easy{{$method->id}}">
                    {{ $method->easy }}
                </div>
                <div class="col-md-2" id="script{{$method->id}}">
                    {{ optional($method->escript)->name }}
                </div>
                <div class="col-md-2" id="comment{{$method->id}}">
                    {{ $method->comment }}
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
<br>