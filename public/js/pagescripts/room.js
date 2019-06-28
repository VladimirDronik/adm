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

    sessionStorage.setItem('imageRoom', linkToImage);
    $("#image").prop('src', '/images/rooms/' + linkToImage);

}


/**
 * Добавление
 *
 * @param string color
 */
function setColor(color)
{

    sessionStorage.setItem('colorRoom', color);
    $("#color").prop('style', 'background: ' + color);

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





