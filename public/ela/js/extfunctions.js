function showErrorModal(message) {
    $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
    $('#info_modal').modal('show');
}

function showSuccessModal(message) {
    $('#info_modal_body').html('<span class="text-info">'+message+'</span>');
    $('#info_modal').modal('show');
}

// todo refactoring

function ajax_html(dataarr, route, outobject)
{

    $.ajax({
        type:'POST',
        url: route,
        data: dataarr,
        success:function(data){

            $(outobject).html(data.html);
        }
    });

}


