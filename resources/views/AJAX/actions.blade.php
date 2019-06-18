@if ($action == 'easy')

    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="dev_select_button" onclick="load_data('device');">Устройство: {{$device}}</button>&nbsp;
    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="port_btn" onclick="load_data('port');">Порт: {{$port}}</button>&nbsp;
    <button type="button" class="btn btn-success  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="action_btn" onclick="load_data('action');">Действие: {{$act}}</button>
    <br><br><div class="alert alert-info">В этом режиме при срабатывании входного порта будет выполняться
        действие с другим портом этого же или другого устройства. Для этого необхоидмо добавить команду
        в формате "Устройство; Порт: Действие"</div>

    @elseif ($action == 'method')
        @if ($object != '')

            Будет выполнен метод связанного объекта
            <!--
        <button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" onclick="">Объект: {{$value}}</button>&nbsp;
        <button type="button" class="btn btn-warning  m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal">Метод:</button>
   -->
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

<script>

    function load_data(mode) {




        var device = {};

        if (mode == 'port') {
            device = ($('#dev_select_button').html()).split(': ');

        }

        if (device[1] == 'отсутствует') {
            alert('Сначала необходимо выбрать устройство');
            mode = 'device';
        }



            var dataarr = {};
            dataarr['mode'] = mode;
            dataarr['device'] = device[1];


            $.ajax({
                type: 'POST',
                url: '/loaddata',
                data: dataarr,
                success: function (data) {

                    $('#method_data').html(data.html);
                    $('#title_action').html(data.title_action);
                }
            });

    }

</script>
