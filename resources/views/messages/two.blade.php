    {{ Form::bs_title('Оповещения') }}

    <div class="container">
        <div class="form-group row">
            <div class="col-3">
            {{ $messagePoint['first'] }}
            </div>
            <div class="col-8">
                <i id="message-txt-on">@if(isset($messages['message_1'])) {{ $messages['message_1'] }} @else Нет оповещения @endif </i>
            </div>
            <div class="col-1">
                <button type="button"
                        data-state="on"
                        class="btn btn-info btn-sm btn-rounded edit_message_btn">
                    <i class="fa fa-cog fa-lg"></i>
                </button>
                <button type="button" data-method="on" class="btn btn-danger btn-rounded btn-sm del_message_btn">
                    <i class="fa fa-trash fa-lg"></i>
                </button>
                <input type="hidden" id="data-priority-on" value="{{ isset($messages['priority_1']) }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-3">
                {{ $messagePoint['second'] }}
            </div>
            <div class="col-8">
                <i id="message-txt-off">@if( isset($messages['message_2']) ) {{ $messages['message_2'] }} @else Нет оповещения @endif </i>
            </div>
            <div class="col-1">
                <button type="button"
                        data-state="off"
                        class="btn btn-info btn-sm btn-rounded edit_message_btn">
                    <i class="fa fa-cog fa-lg"></i>
                </button>
                <button type="button" data-method="off" class="btn btn-danger btn-rounded btn-sm del_message_btn">
                    <i class="fa fa-trash fa-lg"></i>
                </button>
                <input type="hidden" id="data-priority-off" value="{{ isset($messages['priority_2']) }}">
            </div>
        </div>
    </div>
<br>