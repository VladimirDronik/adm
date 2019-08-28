/**
 *  id помещения в локалсторедж для использования в deleteRoom
 *
 * @param id
 */
function idRoom(id) {
    sessionStorage.setItem('idRoom', id);
}

/**
 * Добавление изображения к карточке нового помещения
 *
 * @param string linkToImage
 */
function setImage(linkToImage) {
    let idRoom = sessionStorage.getItem('idSelectRoom');
    sessionStorage.setItem('imageRoom', linkToImage);

    //Добавляем картинку к новому помещению или подменяем картинку у старого
    if (sessionStorage.getItem('updateImage') === 'true') {
        $("#imageRoom_"+idRoom).prop('src', 'ela/images/rooms/' + linkToImage);
        let data = {};
        data['id'] = idRoom;
        data['image'] = linkToImage;
        ajax_html(data, '/rooms/update/image', '');
        sessionStorage.setItem('imageRoom', 'noimage.png');
    } else {
        $("#image").prop('src', 'ela/images/rooms/' + linkToImage);
    }
}

/**
 * Добавление цвета для нового помещения или изменение у существующего
 *
 * @param string color
 */
function setColor(color) {
    let idRoom = sessionStorage.getItem('idSelectRoom');
    //Добавляем картинку к новому помещению или подменяем картинку у старого
    if (sessionStorage.getItem('updateColor') === 'true') {
        let data = {};
        data['id'] = idRoom;
        data['color'] = color;
        $("#colorRoom_"+idRoom).prop('style', 'background: ' + color);
        ajax_html(data, '/rooms/update/color', '');
    } else {
        sessionStorage.setItem('colorRoom', color);
        $("#color").prop('style', 'background: ' + color);
    }
}

/**
 * Изменение названия при щелчке на название помещения в таблице
 *
 * @param int id
 * @param string name
 */
function edit_name(id) {
    $("#nameModalData").val($("#nameRoom_"+id).html());
    sessionStorage.setItem('nameRoom', $("#nameRoom_"+id).html());
    sessionStorage.setItem('idSelectRoom', id);
}

/**
 * Сохранение нового значения для названия помещения
 */
function saveNameRoom() {
    let data = {};
    data['id'] = sessionStorage.getItem('idSelectRoom');
    data['name'] = $("#nameModalData").val().trim();

    ajax_html(data, '/rooms/update/name', '#nameRoom_'+data['id']);
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