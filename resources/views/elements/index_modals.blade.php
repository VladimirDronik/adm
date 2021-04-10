

<!-- модальное окно выбора изображения -->
<div class="modal" id="selectImage">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Выбрать изображение</h4>
            </div>
            <div class="modal-body" style="background: black;">
                @foreach($images as $image)
                    <img src="{{ asset('ela/images/views_items/'.$image) }}" style="cursor: pointer;"
                         onclick="setImage('{{$image}}');"
                         data-dismiss="modal"
                         width="50px"
                         height="50px" >&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- модальное окно выбора изображения для изменения в категории -->
<div class="modal" id="selectImageCategory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Выбрать изображение222</h4>
            </div>
            <div class="modal-body" style="background: black;">
                @foreach($images as $image)
                    <img src="{{ asset('ela/images/views_items/'.$image) }}" style="cursor: pointer;"
                         onclick="setImageCategory('{{$image}}');"
                         data-dismiss="modal"
                         width="50px"
                         height="50px" >&nbsp;&nbsp;&nbsp;
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- модальное окно изменения имени у элемента-->
<div id="nameElementModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Название пункта меню</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control input-default " id="nameModalData"
                       placeholder="Введите название">
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