<div id="message_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="message_modal_title">Редактирование оповещения</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="error_div" style="display: none;">
                    <span id="error_text"></span>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Сообщение*:</strong>
                    </label>
                    <div class="col-md-9">
                        <input class="form-control" required name="m_message" type="text" value="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix">&nbsp;
                    </label>
                    <div class="col-md-9">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-success" id="hight_button">
                                <input type="radio" name="priority" autocomplete="off" value="hight"> Важное оповещение
                            </label>
                            <label class="btn btn-success" id="low_button">
                                <input type="radio" name="priority"  autocomplete="off" value="low"> Обычное оповещение
                            </label>
                        </div>
                    </div>
                </div>

            <input type="hidden" name="m-state" value="">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="apply_message_btn">Изменить уведомление</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_message_btn">Отмена</button>
            </div>
        </div>
    </div>
</div>


