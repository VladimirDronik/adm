<!-- модальное окно добавления нового помещения -->
<div class="modal" id="modalMenu">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modaMenuTitle"> Добавить новый пункт меню</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="type" id="modalType" value="">
                <div id="modal_groups_div">
                    Группа: <select class="form-control input-default col-sm-12" id="modalGroupId">
                        <option value="0">Без группы</option>
                        @foreach($types as $type)
                            <option value="{{ $type[0] }}">{{ $type[1] }}</option>
                        @endforeach
                    </select>
                    <br>
                </div>
                Название: <input type="text" class="form-control input-default col-sm-12" id="nameMenu"
                                           size="15"><br><br>
                Изображение: <img src="{{ asset('ela/images/views_items/noimage.png') }}" id="image"
                                  widtth="50px" height="50px">
                <button data-toggle="modal" data-target="#selectImage" class="btn btn-default btn-sm m-b-5"
                        onclick="updateImage(0, false);"> Выбрать
                </button>
                <br><br>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="storeMenu();">Добавить
                </button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_menu_init_btn" style="display: none;" data-toggle="modal" data-target="#modalMenu">&nbsp;</button>


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