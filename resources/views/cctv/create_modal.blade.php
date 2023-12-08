<!-- модальное окно добавления нового устройтсва видеонаблюдения -->
<div class="modal" id="modalAddDevice">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalAddDeviceTitle"> Добавить новое устройство видеонаблюдения</h4>
            </div>
            <div class="modal-body">
                <a href="{{ route('cameras.create') }}" >
                    Камера
                </a>

                <br><br>

                <a href="{{ route('recorders.create') }}" >
                    Видеорегистратор
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_add_device_init_btn" style="display: none;" data-toggle="modal" data-target="#modalAddDevice">&nbsp;</button>
