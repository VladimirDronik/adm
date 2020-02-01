{{ Form::bs_title('Cобытия') }}
@if($object && count($object->scheduler_tasks))
    <div class="form-group row">
        <label class="col-md-5"><i>Событие</i></label>
        <div class="col-md-5"><i>Метод</i></div>
    </div>
    <div id="events_div">
        @foreach($object->scheduler_tasks as $scheduler_task)
            <div class="form-group row" id="ediv{{$scheduler_task->method}}">
                <label class="col-md-5">
                    <a href="{{ route('events.edit', [$scheduler_task->id]) }}">{{ $scheduler_task->name }}</a>
                </label>
                <div class="col-md-5">
                    {{ optional($scheduler_task->emethod)->name }}
                </div>
            </div>
        @endforeach
    </div>
@else
    <i>Отсутствуют</i>
@endif
