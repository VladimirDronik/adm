


<div style="height:300px;overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Название</th>
                <th>Тип</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($objects as $object)

                <tr>
                    <th scope="row">{{$object->id}}</th>
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

    //Заносим данные в таблицу с помощью ajax

    
    //Отдаем данные странице
    $( '#{{$port}}' ).attr({"class": "btn btn-warning  m-b-10 btn-sm"});
    $( '#{{$port}}' ).html('<b>' + $('#object_'+id).html() + '</b>');
    $( '#{{$port}}').val(id+',' + name + ',' + '{{$port}}');


}

</script>