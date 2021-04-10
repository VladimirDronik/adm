<!-- модальное окно добавления новой страницы -->
<div class="modal" id="modalPage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalPageTitle"> Добавить новую страницу</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="modalType" value="">

                Название: <input type="text" class="form-control input-default col-sm-12" id="namePage"
                                           size="15"><br>

                <div id="modal_groups_div">
                    Тип: <select class="form-control input-default col-sm-12" id="modalGroupId">
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <br>
                </div>

                Ссылка: <input type="text" class="form-control input-default col-sm-12" id="linkPage"
                                 size="15"><br><br>
                <br><br>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="storePage();">Добавить
                </button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_page_init_btn" style="display: none;" data-toggle="modal" data-target="#modalPage">&nbsp;</button>


<!-- модальное окно изменения имени у страницы-->
<div id="namePageModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Название страницы</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control input-default " id="nameModalData"
                       placeholder="Введите название">
                <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveNamePage();">
                    Сохранить изменения
                </button>
            </div>
        </div>
    </div>
</div>


<!-- модальное окно изменения ссылки у страницы-->
<div id="linkPageModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ссылка страницы</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control input-default " id="linkModalData"
                       placeholder="Введите название ссылки">
                <button type="button" class="btn btn-default" onclick="no_link();">Убрать</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveLinkPage();">
                    Сохранить изменения
                </button>
            </div>
        </div>
    </div>
</div>