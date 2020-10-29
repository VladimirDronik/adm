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
        //$('#dev_select_button').html('Устройство: ' + id_device);
        $('#easy_device').text(id_device);
        $('#easy_port').text('отсутствует');

        const url_device = '{{ route('ajax.devices.type_controller') }}';

        $.ajax({
            url: url_device,
            data: {'_token': _token, 'id_device': id_device},
            success: function (data) {

                if(data.type == 'Hite-pro')
                    $('#portordevice').text('Устройство: ');
                else
                    $('#portordevice').text('Порт: ');
               // methods = data.methods;
                //createMethodSelect('#auto_sel_method_off', data.methods, -1);
                //$('#auto_sel_method_off').trigger("chosen:updated");

            }
        });

    }
</script>