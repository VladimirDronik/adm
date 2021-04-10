/**
 *  id помещения в локалсторедж для использования в deleteMenu
 *
 * @param id
 */
function idMenu(id) {
    sessionStorage.setItem('idMenu', id);
}

/**
 * Добавление изображения к карточке нового элемента
 *
 * @param string linkToImage
 */
function setImage(linkToImage) {

    $("#imageElement").prop('src', '/ela/images/views_items/' + linkToImage);
    $("#image").val(linkToImage);

}

/**
 * Добавление изображения к карточке нового помещения
 *
 * @param string linkToImage
 */
function setImageCategory(linkToImage) {
    let idElement = sessionStorage.getItem('idSelectElement');
    sessionStorage.setItem('imageElement', linkToImage);

    //Добавляем картинку к новому пункту меню или подменяем картинку у старого
    if (sessionStorage.getItem('updateImage') === 'true') {
        $("#imageElement_"+idElement).prop('src', '/ela/images/views_items/' + linkToImage);
        let data = {};
        data['id'] = idElement;
        data['image'] = linkToImage;
        ajax_html(data, '/element/update/image', '');
        sessionStorage.setItem('imageElement', 'noimage.png');
    } else {
        $("#image").prop('src', '/ela/images/views_items/' + linkToImage);
    }
}


/**
 * Изменение названия при щелчке на название меню в таблице
 *
 * @param int id
 * @param string name
 */
function edit_name(id) {

    $("#nameModalData").val($("#nameElement_"+id).html());
    sessionStorage.setItem('nameElement', $("#nameElement_"+id).html());
    sessionStorage.setItem('idSelectElement', id);
}

/**
 * Сохранение нового значения для меню
 */
function saveNameMenu() {
    let data = {};
    data['id'] = sessionStorage.getItem('idSelectElement');
    data['name'] = $("#nameModalData").val().trim();

    if (data['name'] == '') {
        $("#nameModalData").val('Без названия');
        data['name'] = 'Без названия';
    }

    ajax_html(data, '/element/update/name', '#nameElement_'+data['id']);
}

/**
 * Убрать название помещения в модальном окне (кнопка убрать)
 */
function no_name() {
    $("#nameModalData").val('Без названия');
}

/**
 * Вывод окна выбора изображений
 *
 * @param int id - id выбранной комнаты
 * @param bool mode - если выбран true, то значит заменяем существующее изображение, false - добавляем новое
 */
function updateImage(id, mode=true) {
    sessionStorage.setItem('idSelectElement', id);
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