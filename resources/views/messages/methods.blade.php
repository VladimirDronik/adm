    {{ Form::bs_title('Оповещения') }}

    <div class="container">
        <div class="form-group row">
            <div class="col-3">
            При включении
            </div>
            <div class="col-8">
                <i id="message-txt-on">@if( $messages['message_on'] ) {{ $messages['message_on'] }} @else Нет оповещения @endif </i>
            </div>
            <div class="col-1">
                <button type="button"
                        data-priority="{{ $messages['priority_on'] }}"
                        data-state="on"
                        class="btn btn-info btn-sm btn-rounded edit_message_btn">
                    <i class="fa fa-cog fa-lg"></i>
                </button>
                <button type="button" data-method="on" class="btn btn-danger btn-rounded btn-sm del_message_btn">
                    <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-3">
                При выключении
            </div>
            <div class="col-8">
                <i id="message-txt-off">@if( $messages['message_off'] ) {{ $messages['message_off'] }} @else Нет оповещения @endif </i>
            </div>
            <div class="col-1">
                <button type="button"
                        data-priority="{{ $messages['priority_off'] }}"
                        data-state="off"
                        class="btn btn-info btn-sm btn-rounded edit_message_btn">
                    <i class="fa fa-cog fa-lg"></i>
                </button>
                <button type="button" data-method="off" class="btn btn-danger btn-rounded btn-sm del_message_btn">
                    <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>
        </div>
    </div>



<br>