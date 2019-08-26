/**
 * Добавление нового помещения
 */
function addRoom()
{

    var dataarr = {};
    dataarr['name'] = $("#nameRoom").val();
    dataarr['image'] = sessionStorage.getItem('imageRoom');
    dataarr['color'] = sessionStorage.getItem('colorRoom');


    ajax_html(dataarr, '/rooms/addRoom', '');

    //Переадресация на страницу помещений
    window.location.href = '/rooms';
}


/**
 * Удаление помещения
 */
function deleteRoom()
{
    var dataarr = {};

    dataarr['id'] = sessionStorage.getItem('idRoom');;

    ajax_html(dataarr, '/rooms/deleteRoom', '');

    //Переадресация на страницу устройств
    window.location.href = '/rooms';

}

/**
 *  id помещения в локалсторедж для использования в deleteRoom
 *
 * @param id
 */
function idRoom(id)
{
    sessionStorage.setItem('idRoom', id);
}


/**
 * Добавление изображения к карточке нового помещения
 *
 * @param string linkToImage
 */
function setImage(linkToImage)
{

    var idRoom = sessionStorage.getItem('idSelectRoom');

    sessionStorage.setItem('imageRoom', linkToImage);

    //Добавляем картинку к новому помещению или подменяем картинку у старого
    if (sessionStorage.getItem('updateImage') === 'true') {

        $("#imageRoom_"+idRoom).prop('src', 'ela/images/rooms/' + linkToImage);

        var dataarr = {};

        dataarr['id'] = idRoom;
        dataarr['image'] = linkToImage;

        ajax_html(dataarr, '/rooms/updateImage', '');

    }
        else {
            $("#image").prop('src', 'ela/images/rooms/' + linkToImage);
        }


}


/**
 * Добавление цвета для нового помещения или изменение у существующего
 *
 * @param string color
 */
function setColor(color)
{

    var idRoom = sessionStorage.getItem('idSelectRoom');

    //Добавляем картинку к новому помещению или подменяем картинку у старого
    if (sessionStorage.getItem('updateColor') === 'true') {

        var dataarr = {};
        dataarr['id'] = idRoom;
        dataarr['color'] = color;

        $("#colorRoom_"+idRoom).prop('style', 'background: ' + color);

        ajax_html(dataarr, '/rooms/updateColor', '');

    } else {
        sessionStorage.setItem('colorRoom', color);
        $("#color").prop('style', 'background: ' + color);
    }

}



/**
 * Запись в БД нового значения сортировки
 *
 * @param id
 */
function changeSort(id, sort, direction) {

    var dataarr = {};

    dataarr['id'] = id;
    dataarr['sort'] = sort;
    dataarr['direction'] = direction;

    ajax_html(dataarr, '/rooms/sort', '');

    //Переадресация на страницу помещений
    window.location.href = '/rooms';
}


/**
 * Изменение названия при щелчке на название помещения в таблице
 *
 * @param int id
 * @param string name
 */
function edit_name(id)
{
    $("#nameModalData").val($("#nameRoom_"+id).html());
    sessionStorage.setItem('nameRoom', $("#nameRoom_"+id).html());
    sessionStorage.setItem('idSelectRoom', id);
}


/**
 * Сохранение нового значения для названия помещения
 */
function saveNameRoom() {

    var dataarr = {};

    dataarr['idSelectRoom'] = sessionStorage.getItem('idSelectRoom');
    dataarr['nameRoom'] = $("#nameModalData").val();

    ajax_html(dataarr, '/rooms/saveNameRoom', '#nameRoom_'+dataarr['idSelectRoom']);
}


/**
 * Убрать название помещения в модальном окне (кнопка убрать)
 */
function no_name() {

    $("#nameModalData").val('Без названия');
}




/**
 * Вывод окна выбора изображений для замены у текущего помещения
 *
 * @param int id - id выбранной комнаты
 * @param bool mode - если выбран true, то значит заменяем существующее изображение, false - добавляем новое
 */
function updateImage(id, mode=true) {

    sessionStorage.setItem('idSelectRoom', id);
    sessionStorage.setItem('updateImage', mode);
}


/**
 * Вывод окна выбора цвета для замены у текущего помещения
 *
 * @param int id - id выбранной комнаты
 * @param bool mode - если выбран true, то значит заменяем существующий цвет, false - добавляем новый
 */
function updateColor(id, mode=true) {
    sessionStorage.setItem('idSelectRoom', id);
    sessionStorage.setItem('updateColor', mode);
}