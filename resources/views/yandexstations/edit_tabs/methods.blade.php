<div class="form-group row">
    @can('superadmin')
        <div class="col-md-1"><i>ID</i></div>
    @endcan
    <label class="col-md-3"><i>Название метода</i></label>
    <div class="col-md-2"><i>Скрипт</i></div>
    <div class="col-md-5"><i>Комментарий</i></div>
    <div class="col-md-1 text-right"></div>
</div>
<div id="methods_div">
    @foreach($object->methods as $method)
        <div class="form-group row" id="div{{$method->id}}">
            @can('superadmin')
                <label class="col-md-1" id="methodid{{$method->id}}">
                    {{$method->id}}
                </label>
            @endcan
            <label class="col-md-3" id="name{{$method->id}}">
                {{$method->name}}
            </label>
            <div class="col-md-2" id="script{{$method->id}}">
                {{ optional($method->escript)->name }}
            </div>
            <div class="col-md-5" id="comment{{$method->id}}">
                {{ $method->comment }}
            </div>
        </div>
    @endforeach
</div>
<br>