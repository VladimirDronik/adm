<!-- модальное окно авторизации по паролю яндекс -->
<div id="auth_modal" class="modal">
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Введите логин и пароль или одноразовый ключ от вашего аккаунта яндекс</h5>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 label-fix" for="yaLogin">
                        <strong>Логин:</strong>
                    </label>
                    <div class="col-md-9">
                        <input id="yaLogin" class="form-control" name="ya_login" autocomplete="off" required type="email">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 label-fix" for="yaPassword">
                        <strong>Пароль:</strong>
                    </label>
                    <div class="col-md-9">
                        <input id="yaPassword" class="form-control" name="ya_password" autocomplete="off" required type="password">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="send_yandex_auth" class="btn btn-success m-b-10 m-l-5" data-dismiss="modal">Войти</button>
                <button type="button" class="btn btn-outline-danger m-b-10 m-l-5 pull-right" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="password_auth_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#auth_modal">&nbsp;</button>
