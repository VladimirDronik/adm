<div style="height:300px;overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Тип</th>
                </tr>
            </thead>
            <tbody>
            @foreach($objects as $object)
                <tr>
                    <td><a href="#" id="object_{{$object->id}}" onclick="select_object({{$object->id}},'{{$object->name}}')" data-dismiss="modal" >{{$object->name}} </a></td>
                    <td>{{$object->type}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function select_object(id, name) {
    let port_name = '{{$port}}';
    let arr_port = port_name.split('_');

    //Заносим данные в таблицу с помощью ajax

    let data = {};
    data['id_object'] = id;
    data['id_port'] = arr_port[1];

    $.ajax({
        url: '/add_object_to_port',
        data: data,
        success:function (data) {
         //alert(data.status);
         //   $("#objectframe").html(data.html);
        }
    });

    //Отдаем данные странице
    let html = $('#object_'+id).html();
    if (typeof html === 'undefined') {
        html = 'Отсутствует';
    } else {
        html = '<b>' + html + '</b>';
    }
    $( '#{{$port}}' ).attr({"class": "btn btn-warning  m-b-10 btn-sm"});
    $( '#{{$port}}' ).html(html);
    $( '#{{$port}}').val(id+',' + name + ',' + '{{$port}}');
}
</script>