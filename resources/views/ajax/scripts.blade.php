<div style="height:300px;overflow:auto;">
    @if(count($scripts))
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="text-left">Название</th>
                </tr>
            </thead>
            <tbody>
            @foreach($scripts as $script)
                <tr>
                    <td>{{$script->id}}</td>
                    <td class="text-left">
                        <a href="#" id="object_{{$script->id}}"
                           onclick="select_script({{$script->id}}, '{{$script->name}}')"
                           data-dismiss="modal" >{{$script->name}}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p>Скрипты не найдены</p>
    @endif
</div>
<script>
    function select_script(id_script, name_script) {
        $('#script_btn').html('Скрипт: ' + name_script);
        $('#id_script').val(id_script);
    }
</script>