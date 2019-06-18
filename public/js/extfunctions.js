/**
 * Created by kinord on 12.06.19.
 */


function ajax_html(dataarr, route, outobject) {

    $.ajax({
        type:'POST',
        url: route,
        data: dataarr,
        success:function(data){

            $(outobject).html(data.html);
        }
    });

}


