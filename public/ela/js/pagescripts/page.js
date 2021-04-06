/**
 *  id помещения в локалсторедж для использования в deleteMenu
 *
 * @param id
 */
function idMenu(id) {
    sessionStorage.setItem('idMenu', id);
}

/**
 * Добавление изображения к карточке нового помещения
 *
 * @param string linkToImage
 */
function setImage(linkToImage) {
    let idMenu = sessionStorage.getItem('idSelectMenu');
    sessionStorage.setItem('imageMenu', linkToImage);

    //Добавляем картинку к новому пункту меню или подменяем картинку у старого
    if (sessionStorage.getItem('updateImage') === 'true') {
        $("#imageMenu_"+idMenu).prop('src', '/ela/images/views_items/' + linkToImage);
        let data = {};
        data['id'] = idMenu;
        data['image'] = linkToImage;
        ajax_html(data, '/menu/update/image', '');
        sessionStorage.setItem('imageMenu', 'noimage.png');
    } else {
        $("#image").prop('src', '/ela/images/views_items/' + linkToImage);
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
 * Изменение названия при щелчке на название страницы в таблице
 *
 * @param int id
 * @param string name
 */
function edit_name(id) {

    $("#nameModalData").val($("#namePage_"+id).html());
    sessionStorage.setItem('namePage', $("#namePage_"+id).html());
    sessionStorage.setItem('idSelectPage', id);
}

/**
 * Изменение ссылки для страницы
 * @param id
 */
function edit_link(id) {

    $("#linkModalData").val($("#linkPage_"+id).html());
    sessionStorage.setItem('linkPage', $("#linkPage_"+id).html());
    sessionStorage.setItem('idSelectPage', id);
}

/**
 * Сохранение нового значения для страницы
 */
function saveNamePage() {
    let data = {};
    data['id'] = sessionStorage.getItem('idSelectPage');
    data['name'] = $("#nameModalData").val().trim();

    if (data['name'] == '') {
        $("#nameModalData").val('Без названия');
        data['name'] = 'Без названия';
    }

    ajax_html(data, '/page/update/name', '#namePage_'+data['id']);
}

/**
 * Сохранение нового значения ссылки для страницы
 */
function saveLinkPage() {
    let data = {};
    data['id'] = sessionStorage.getItem('idSelectPage');
    data['link'] = $("#linkModalData").val().trim();

    if (data['link'] == '') {
        $("#linkModalData").val('Нет ссылки');
        data['link'] = 'Нет ссылки';
    }

    ajax_html(data, '/page/update/link', '#linkPage_'+data['id']);
}

/**
 * Убрать название страницы в модальном окне (кнопка убрать)
 */
function no_name() {
    $("#nameModalData").val('Без названия');
}

/**
 * Убрать ссылку в модальном окне (кнопка убрать)
 */
function no_link() {
    $("#linkModalData").val('');
}


/**
 * Вывод окна выбора изображений для замены у текущего помещения
 *
 * @param int id - id выбранной комнаты
 * @param bool mode - если выбран true, то значит заменяем существующее изображение, false - добавляем новое
 */
function updateImage(id, mode=true) {
    sessionStorage.setItem('idSelectMenu', id);
    sessionStorage.setItem('updateImage', mode);
}

/**
 * Вывод окна выбора цвета для замены у текущего помещения
 *
 * @param int id - id выбранной комнаты
 * @param bool mode - если выбран true, то значит заменяем существующий цвет, false - добавляем новый
 */
function updateColor(id, mode=true) {
    sessionStorage.setItem('idSelectMenu', id);
    sessionStorage.setItem('updateColor', mode);
}

function changeSort(id, direction) {
    $.ajax({
        url: sortUrl,
        data: {'_token': _token, 'id': id, 'direction': direction},
        success: function (data) {
            if (data.result) {
                window.location.href = url;
            } else {
                showErrorModal('Ошибка при сохранении изменений');
            }
        }
    });
}

function del() {
    $('#del_modal').modal('hide');
    if (del_id) {
        $.ajax({
            url: deleteUrl,
            data: {'_token': _token, 'id': del_id},
            success: function (data) {
                if (data.result) {
                    window.location.href = url;
                } else {
                    showErrorModal('Ошибка при удалении');
                }
            }
        });
    }
}

function storeMenu() {
    const name = $("#modalMenu #nameMenu").val().trim();
    const image = sessionStorage.getItem('imageMenu');
    const style = sessionStorage.getItem('colorMenu');
    const type = $("#modalMenu #modalType").val();
    const parent = $("#modalMenu #modalGroupId").val();

    sessionStorage.setItem('imageMenu', 'noimage.png');

    $.ajax({
        url: storeUrl,
        data: {'_token': _token, 'name': name, 'image': image,
            'style': style, 'parent': parent, 'type': type},
        success: function (data) {
            if (data.result) {
                window.location.href = url;
            } else {
                showErrorModal('Ошибка при добавлении меню');
            }
        }
    });



}