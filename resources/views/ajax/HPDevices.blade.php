<div style="height:300px;overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="text-left">Название</th>
                </tr>
            </thead>
            <tbody>
            @foreach($devices as $device)
                <tr>
                    <td><a href="#" id="object_{{$device->id}}"
                           onclick="select_port({{$device->id}})"
                           data-dismiss="modal" >{{$device->id}}</a></td>
                    <td class="text-left"><a href="#" id="object_{{$device->id}}"
                           onclick="select_port({{$device->id}})"
                           data-dismiss="modal" >{{$device->name}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function select_port(device) {
        $('#port_btn').html('<span id="portordevice">Устройство: </span>' + '<span id="easy_port">'+device+'</span>');
       // $('#easy_port').text(device);
    }
</script>