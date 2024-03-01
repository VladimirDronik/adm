<div class="form-group row">
    @can('superadmin')
        <div class="col-md-1"><i>ID</i></div>
    @endcan
    <label class="col-md-5"><i>Название метода</i></label>
    <div class="col-md-4"><i>Скрипт</i></div>
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
            <label class="col-md-4" id="name{{$method->id}}">
                {{$method->name}}
            </label>
            @if($method->comment)
                <div class="col-md-1" id="comment{{$method->id}}">
                    <img src="{{ asset('ela/images/info.png') }}" width="23" height="23" title="{{ $method->comment }}"></img>
                </div>
            @endif
            <div class="col-md-4" id="script{{$method->id}}">
                {{ optional($method->escript)->name }}
            </div>
        </div>
    @endforeach
</div>
<br>