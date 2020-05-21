    {{ Form::bs_title('Оповещения') }}

    <div class="container">
        <div class="form-group row">
            <div class="col-3">
            При включении
            </div>
            <div class="col">
                Нет оповещения
            </div>
            <div class="col">
                <button type="button" data-object="111" data-method="222"
                        data-priority="1"  data-state="ON"  data-message="message"
                        class="btn btn-info btn-sm btn-rounded edit_message_btn">
                    <i class="fa fa-cog fa-lg"></i>
                </button>
                <button type="button" data-id="222" data-name="333" class="btn btn-danger btn-rounded btn-sm del_btn">
                    <i class="fa fa-trash fa-lg"></i>
                </button>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-3">
                При выключении
            </div>
            <div class="col">
                Нет оповещения
            </div>
            <div class="col">
                <button id="add_btn3" type="button" class="btn btn-primary">
                    <i class="fa fa-plus fa-lg"></i> Добавить
                </button>
            </div>
        </div>
    </div>



<br>