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
                    <td><a href="#" id="object_{{$object->id}}"
                           onclick="selectObject({{$object->id}},'{{$object->name}}')"
                           data-dismiss="modal" >{{$object->name}} </a></td>
                    <td>{{$object->type}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function selectObject(id, name) {
        let view_name = '{{$view}}';
        let arr_view = view_name.split('_');

        //Заносим данные в таблицу с помощью ajax

        let data = {};
        data['id_object'] = id;
        data['id_view'] = arr_view[1];

        $.ajax({
            url: '/add_object_to_view',
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
        $( '#{{$view}}' ).attr({"class": "btn btn-warning  m-b-10 btn-sm"});
        $( '#{{$view}}' ).html(html);
        $( '#{{$view}}').val(id+',' + name + ',' + '{{$view}}');
    }
</script>