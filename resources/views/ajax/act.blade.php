<div class="button-list">
    <button type="button" class="btn btn-primary btn-block m-b-10" data-dismiss="modal" onclick="select_act(0);">0: Выключить</button>
    <button type="button" class="btn btn-primary btn-block m-b-10" data-dismiss="modal" onclick="select_act(1);">1: Включить</button>
    <button type="button" class="btn btn-primary btn-block m-b-10" data-dismiss="modal" onclick="select_act(2);">2: Переключить</button>
</div>
<script>
    function select_act(mode) {
        //$('#action_btn').html('Действие: ' + mode);
        $('#easy_action').text(mode);
    }
</script>