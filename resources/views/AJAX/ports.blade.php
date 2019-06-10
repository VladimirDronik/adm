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
            @foreach ($ports as $port)

                <tr>
                    <th scope="row">{{$port->num_port}}</th>
                    <td><a href="#" id="object_{{$port->id}}" onclick="select_port({{$port->num_port}})" data-dismiss="modal" >{{$port->comment}} </a></td>

                </tr>


            @endforeach




            </tbody>
        </table>
    </div>
</div>

<script>
    function select_port(port)
    {

        $('#port_btn').html('Порт: ' + port);

    }
</script>