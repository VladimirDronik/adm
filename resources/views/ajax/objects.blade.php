<div style="height:300px;overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
{{--                    <th>ID</th>--}}
                    <th>Название</th>
                    <th>Тип</th>
                </tr>
            </thead>
            <tbody>
            @foreach($objects as $object)
                <tr>
{{--                    <th scope="row">{{$object->id}}</th>--}}
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
    var port_name = '{{$port}}';
    var arr_port = port_name.split('_');

    //Заносим данные в таблицу с помощью ajax

    var data = {};
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
    $( '#{{$port}}' ).attr({"class": "btn btn-warning  m-b-10 btn-sm"});
    $( '#{{$port}}' ).html('<b>' + $('#object_'+id).html() + '</b>');
    $( '#{{$port}}').val(id+',' + name + ',' + '{{$port}}');
}
</script>