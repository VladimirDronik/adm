function showErrorModal(message)
{

}

function showSuccessModal(message)
{

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


