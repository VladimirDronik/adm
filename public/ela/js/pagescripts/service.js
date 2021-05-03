/**
 * Общие сервисные функции
 */


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
