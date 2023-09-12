<!-- модальное окно выбора способа авторизации яндекс -->
<div id="auth_methods_modal" class="modal">
    <div class="modal-dialog" style="max-width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Выберите способ авторизации:</h5>
            </div>
            <div class="modal-body">
                <div>
                    <button type="button" id="qr_yandex_auth" class="btn btn-primary m-b-10 m-l-5" data-dismiss="modal">QR-код</button>
                </div>
                <div>
                    <button type="button" id="password_yandex_auth" class="btn btn-primary m-b-10 m-l-5" data-dismiss="modal">Пароль или одноразовый ключ</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger m-b-10 m-l-5 pull-right" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="auth_methods_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#auth_methods_modal">&nbsp;</button>
