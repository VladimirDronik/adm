<div style="height:300px;overflow:auto;">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th class="text-left">Название</th>
            </tr>
            </thead>
            <tbody>
            @foreach($devices as $device)
                <tr>
                    <td>{{$device->id}}</td>
                    <td class="text-left"><a href="#" id="object_{{$device->id}}"
                           onclick="select_device({{$device->id}})"
                           data-dismiss="modal" >{{$device->description}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function select_device(id_device) {
        $('#dev_select_button').html('Устройство: ' + id_device);
    }
</script>