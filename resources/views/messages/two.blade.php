    {{ Form::bs_title('Оповещения') }}

    <div class="container">
        <div class="form-group row">
            <div class="col-3">
            {{ $messages['first'] }}
            </div>
            <div class="col-8">
                <i id="message-txt-on">@if( $messages['message_on'] ) {{ $messages['message_on'] }} @else Нет оповещения @endif </i>
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
                <input type="hidden" id="data-priority-on" value="{{ $messages['priority_on'] }}">
            </div>
        </div>

        <div class="form-group row">
            <div class="col-3">
                {{ $messages['second'] }}
            </div>
            <div class="col-8">
                <i id="message-txt-off">@if( $messages['message_off'] ) {{ $messages['message_off'] }} @else Нет оповещения @endif </i>
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
                <input type="hidden" id="data-priority-off" value="{{ $messages['priority_off'] }}">
            </div>
        </div>
    </div>



<br>