/**
 * Общие сервисные функции
 */

//Подтверждение модельного окна удаления
$('#del_modal_btn').click(clickDelBtn);

function serviceInit() {

    //Показать настройки при активации управления устройством через Алису
    $('#alice_checkbox').click(function(){
        if ($(this).is(':checked')){
            $('#div_command').show(100);
        } else {
            $('#div_command').hide(100);
        }
    });
}

