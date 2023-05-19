
<!-- <br>
{{ Form::bs_title('Настраиваемые события') }}

<div class="form-group row">

    <div class="col-md-1"><i>ID</i></div>
    <div class="col-md-3 text-left"><i>Название события</i></div>
    <div class="col-md-3 text-left"><i>Условие</i></div>
    <div class="col-md-3 text-left"><i>Действие</i></div>
    <div class="col-md-1 text-right"></div>

</div>


<div id="events_div">

    @foreach($events as $event)

            <div class="form-group row" id="div{{$event->id}}">

                @php
                    $allEvents .= $event->id.',';
                @endphp


                <label class="col-md-1" id="eventid{{$event->id}}">
                    {{$event->id}}
                </label>
                <label class="col-md-3" id="name{{$event->id}}">
                    {{$event->name}}
                </label>
                <label class="col-md-3" id="condition{{$event->id}}">
                    {{$event->property.' '.$event->comparison.' '.$event->value}}
                </label>
                <input type="hidden" id="property{{$event->id}}" value="{{$event->property}}">
                <input type="hidden" id="comparison{{$event->id}}" value="{{$event->comparison}}">
                <input type="hidden" id="ev_value{{$event->id}}" value="{{$event->value}}">
                <input type="hidden" id="m_event{{$event->id}}" value="{{$event->event}}">

                <div class="col-md-2" style="font-family: 'FontAwesome', Helvetica;" id="action{{$event->id}}">


                </div>
                <div class="col-md-1 text-right">
                    @if(!$event->is_system)
                        <button type="button"
                                data-id="{{ $event->id }}"
                                data-id_object="{{ $event->id_object }}"
                                data-event="{{ $event->event }}"
                                data-property="{{ $event->property }}"
                                data-comparison="{{ $event->comparison }}"
                                data-value="{{ $event->value }}"
                                class="btn btn-info btn-sm btn-rounded editEvent_btn">
                            <i class="fa fa-cog fa-lg"></i>
                        </button>
                        <button type="button" data-id="{{ $event->id }}" data-name="{{ $event->name }}" class="btn btn-danger btn-rounded btn-sm delEvent_btn">
                            <i class="fa fa-trash fa-lg"></i>
                        </button>
                    @endif
                </div>
            </div>


    @endforeach
        <input type="hidden" id="allevents" value="{{$allEvents}}">
        <input type="hidden" id="data-holder">
</div>
<div class="form-group row">
    <div class="col-md-12 text-left">
        <button id="addEvent_btn" type="button" class="btn btn-primary">
            <i class="fa fa-plus fa-lg"></i> Добавить событие
        </button>
    </div>
</div>

@include('objects.event_modal')
@include('objects.action_modal')
<button type="button" id="init_event_btn" style="display: none;" data-toggle="modal" data-target="#event_modal">&nbsp;</button>
<button type="button" id="init_action_btn" style="display: none;" data-toggle="modal" data-target="#action_modal"> -->


<script>
    const url_actions = '{{ route('ajax.actions.getForEvent') }}';



/*
    document.addEventListener('DOMContentLoaded', function() {

        // отслеживаем активность вкладки браузера
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {

                if($('#data-holder').val())
                loadActions(Number($('#data-holder').val()));
            }

        });

    });
    */
</script>
