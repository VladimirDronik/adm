<!-- модальное окно запроса удаления пунктов меню связанных с инженерным устройтсвом -->
<div id="del_menu_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="del_menu_modal_title">Подтверждение</h5>
            </div>
            <div class="modal-body text-left" id="del_menu_modal_body" style="font-size: larger;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="del_menu_modal_btn" data-dismiss="modal">Удалить</button>
                <button type="button" class="btn btn-default" id="del_menu_cancel_btn" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<button type="button" id="del_menu_init_btn" style="display: none;" data-toggle="modal" data-target="#del_menu_modal">&nbsp;</button>
