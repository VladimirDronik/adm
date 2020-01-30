function showErrorModal(message) {
    $('#info_modal_body').html('<span class="text-danger">'+message+'</span>');
    $('#info_init_btn').click();
}

function showSuccessModal(message) {
    $('#info_modal_body').html('<span class="text-info">'+message+'</span>');
    $('#info_init_btn').click();
}

function ajax_html(data, route, outobject) {
    $.ajax({
        type:'POST',
        url: route,
        data: data,
        success: function (data) {
            $(outobject).html(data.html);
            if (data.reload) {
                location.href = location.href;
            }
        }
    });
}


