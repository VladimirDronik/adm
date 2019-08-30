@if ($action == 'easy')
    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="dev_select_button" onclick="load_data('device');">Устройство: {{$device}}</button>&nbsp;
    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="port_btn" onclick="load_data('port');">Порт: {{$port}}</button>&nbsp;
    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="action_btn" onclick="load_data('action');">Действие: {{$act}}</button>
    <br><br><div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться
        действие с другим портом этого же или другого устройства. Для этого необхоидмо добавить команду
        в формате "Устройство; Порт: Действие"</div>
@elseif ($action == 'method')
        @if ($object != '')
            Будет выполнен метод связанного объекта<br>
            <button id="method_btn" type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" onclick="load_data('method',{{$object}});" data-target="#methodsModal">Метод: {{ $value }}</button>
        @else
            Для активации необходимо выбрать объект
        @endif
   <br>
   <br>
    <div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться
        метод выбранного здесь объекта</div>
@elseif ($action == 'script')
    <button type="button" class="btn btn-info  m-b-10 btn-sm" data-toggle="modal" id="script_btn" onclick="load_data('script');" data-target="#methodsModal">Скрипт: {{$value}}</button>
@else
    <div class="alert alert-info">Действие при срабатывании порта не выбрано</div>
@endif

<input type="hidden" id="action_text" value="{{$action}}">
<input type="hidden" id="id_script" value="">
<input type="hidden" id="port_id" value="{{$port_id}}">
<input type="hidden" id="id_object" value="{{$object}}">
<input type="hidden" id="method_id" value="">
<input type="hidden" id="method_name" value="">
<script>
    function load_data(mode, object_id) {
        var device = {};

        if (mode == 'port') {
            device = ($('#dev_select_button').html()).split(': ');

        }

        if (device[1] == 'отсутствует') {
            alert('Сначала необходимо выбрать устройство');
            mode = 'device';
        }

        let data = {};
        data['mode'] = mode;
        data['device'] = device[1];
        data['object_id'] = object_id;

        $.ajax({
            type: 'POST',
            url: '/loaddata',
            data: data,
            success: function (data) {
                $('#method_data').html(data.html);
                $('#title_action').html(data.title_action);
            }
        });
    }
</script>
