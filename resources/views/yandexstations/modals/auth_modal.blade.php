<!-- модальное окно авторизации яндекс -->
<div id="auth_modal" class="modal">
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Введите код подтверждения, полученный от яндекса</h5>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="control-label text-right col-md-1 label-fix" for="yaCode">
                        <strong>Код:</strong>
                    </label>
                    <div class="col-md-11">
                        <input id="yaCode" class="form-control" name="ya_code" autocomplete="off" required type="number">
                        <small class="form-control-feedback">Если у вас нет кода пожтверждения, закройте окно и нажмите на кнопку "Получить код для синхронизации"</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="send_yandex_auth" class="btn btn-success m-b-10 m-l-5" data-dismiss="modal">Синхронизировать устройства</button>
                <button type="button" class="btn btn-outline-danger m-b-10 m-l-5 pull-right" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_auth_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#auth_modal">&nbsp;</button>
