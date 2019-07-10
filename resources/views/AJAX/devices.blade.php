<div style="height:300px;overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Название</th>


            </tr>
            </thead>
            <tbody>
            @foreach ($devices as $device)

                <tr>
                    <th scope="row">{{$device->id}}</th>
                    <td><a href="#" id="object_{{$device->id}}" onclick="select_device({{$device->id}})" data-dismiss="modal" >{{$device->description}} </a></td>

                </tr>


            @endforeach




            </tbody>
        </table>
    </div>
</div>


<script>
    function select_device(id_device)
    {

        $('#dev_select_button').html('Устройство: ' + id_device);

    }
</script>