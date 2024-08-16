<!-- модальное окно изменения имени у элемента-->
<div id="nameElementModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Название пункта меню</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control input-default " id="nameModalData" placeholder="Введите название">
                <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveNameMenu();">
                    Сохранить изменения
                </button>
            </div>
        </div>
    </div>
</div>
