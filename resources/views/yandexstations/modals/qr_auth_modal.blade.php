<!-- модальное окно авторизации по QR-коду яндекс -->
<div id="qr_auth_modal" class="modal">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Авторизация по QR-коду</h5>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <strong>Отсканируйте QR-код и пройдите авторизацию:</strong>
                    <img src="" id="qr_code_image" width="265px" height="265px" loading="lazy">
                </div>
                <br>
                <div class="col-md-12">
                    <strong>После успешной авторизации, нажмите "Продолжить"</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="send_yandex_qr_auth" class="btn btn-success m-b-10 m-l-5" data-dismiss="modal">Продолжить</button>
                <button type="button" class="btn btn-outline-danger m-b-10 m-l-5 pull-right" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="qr_auth_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#qr_auth_modal">&nbsp;</button>
