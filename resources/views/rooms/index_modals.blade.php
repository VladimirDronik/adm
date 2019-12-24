
<!-- модальное окно добавления новой группы -->
<div class="modal" id="addNewGroup">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Добавить новую группу</h4>
            </div>
            <div class="modal-body">
                Название группы: <input type="text" class="form-control input-default col-sm-12" id="nameGroup"
                                           size="15"><br><br>
                Изображение: <img src="{{ asset('ela/images/rooms/noimage.png') }}" id="image"
                                  style="background: black;">
                <button data-toggle="modal" data-target="#selectImage" class="btn btn-default btn-sm m-b-5"
                        onclick="updateImage(0, false);"> Выбрать
                </button>
                <br><br>
                Цветовая схема: <label class="btn btn-default" id="color"></label> &nbsp; &nbsp;
                <button data-toggle="modal" data-target="#selectColor" onclick="updateColor({{ 0 }}, false)"
                        class="btn btn-default btn-sm m-b-5">Выбрать
                </button>
                <br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="storeGroup();">Добавить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- модальное окно добавления нового помещения -->
<div class="modal" id="addNewRoom">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Добавить новое помещение</h4>
            </div>
            <div class="modal-body">
                Название помещения: <input type="text" class="form-control input-default col-sm-12" id="nameRoom"
                                           size="15"><br><br>
                Изображение: <img src="{{ asset('ela/images/rooms/noimage.png') }}" id="image"
                                  style="background: black;">
                <button data-toggle="modal" data-target="#selectImage" class="btn btn-default btn-sm m-b-5"
                        onclick="updateImage(0, false);"> Выбрать
                </button>
                <br><br>
                Цветовая схема: <label class="btn btn-default" id="color"></label> &nbsp; &nbsp;
                <button data-toggle="modal" data-target="#selectColor" onclick="updateColor({{ 0 }}, false)"
                        class="btn btn-default btn-sm m-b-5">Выбрать
                </button>
                <br><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="storeRoom();">Добавить
                </button>
            </div>
        </div>
    </div>
</div>

<!-- модальное окно выбора изображения -->
<div class="modal" id="selectImage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Выбрать изображение</h4>
            </div>
            <div class="modal-body" style="background: black;">
                @foreach($images as $image)
                    <img src="{{ asset('ela/images/rooms/'.$image) }}" style="cursor: pointer;"
                         onclick="setImage('{{$image}}');"
                         data-dismiss="modal">&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- модальное окно выбора цвета -->
<div class="modal" id="selectColor">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Выбрать цвет</h4>
            </div>
            <div class="modal-body">
                @foreach($colors as $color)
                    <button style="background:{{$color->name}};" data-dismiss="modal" class="btn btn-default m-b-10"
                            onclick="setColor('{{$color->name}}'); ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- модальное окно изменения имени у помещения-->
<div id="nameRoomModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Название помещения</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control input-default " id="nameModalData"
                       placeholder="Введите название">
                <button type="button" class="btn btn-default" onclick="no_name();">Убрать</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="saveNameRoom();">
                    Сохранить изменения
                </button>
            </div>
        </div>
    </div>
</div>