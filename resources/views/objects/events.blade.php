
<br>

<div class="form-group row">

    <div class="col-md-2"><i>ID</i></div>
    <label class="col-md-4"><i>Название события</i></label>
    <div class="col-md-6 text-left"><i>Действие</i></div>
    <div class="col-md-1 text-right"></div>

</div>

<div id="methods_div">
    @foreach($events as $event)

            <div class="form-group row" id="div{{$event->id}}">

                <label class="col-md-1" id="methodid{{$event->id}}">
                    {{$event->id}}
                </label>
                <label class="col-md-3" id="name{{$event->id}}">
                    {{$event->name}}
                </label>
                <label class="col-md-3" id="name{{$event->id}}">
                    {{$event->condition}}
                </label>
                <div class="col-md-2" id="comment{{$event->id}}">
                    {{ $event->comment }}
                </div>
                <div class="col-md-1 text-right">
                    @if(!$event->is_system)
                        <button type="button" data-id="{{ $event->id }}"
                                class="btn btn-info btn-sm btn-rounded edit_btn">
                            <i class="fa fa-cog fa-lg"></i>
                        </button>
                        <button type="button" data-id="{{ $event->id }}" data-name="{{ $event->name }}" class="btn btn-danger btn-rounded btn-sm del_btn">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    @endif
                </div>
            </div>

    @endforeach
</div>
<div class="form-group row">
    <div class="col-md-12 text-left">
        <button id="add_btn" type="button" class="btn btn-primary">
            <i class="fa fa-plus fa-lg"></i> Добавить событие
        </button>
    </div>
</div>