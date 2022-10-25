/**
 *  id помещения в локалсторедж для использования в deleteMenu
 *
 * @param id
 */
function idMenu(id) {
    sessionStorage.setItem('idMenu', id);
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

function addMenu(idObject) {
alert('------'+idObject);
    //$('#del_modal').modal('hide');

  //  if (del_id) {
   //     $.ajax({
  //          url: addMenuUrl,
   //         data: {'_token': _token, 'id': idObject},
   //         success: function (data) {
   //             if (data.result) {
    //                window.location.href = url;
   //             } else {
    //                showErrorModal('Ошибка при создании нового пункта меню');
    //            }
    //        }
    //    });
   // }
}

function storePage() {

    const name = $("#modalPage #namePage").val().trim();
    const link = $("#modalPage #linkPage").val().trim();
    const type = $("#modalPage #modalType").val();


    $.ajax({
        url: storeUrl,
        data: {'_token': _token, 'name': name,
            'type': type, 'link': link},
        success: function (data) {
            if (data.result) {
                window.location.href = url;
            } else {
                showErrorModal('Ошибка при добавлении страицы');
            }
        }
    });



}
