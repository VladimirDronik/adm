<!-- модальное окно добавления нового устройтсва освещения -->
<div class="modal" id="modalAddIllumination">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalAddDeviceTitle"> Добавить новое устройство освещения</h4>
            </div>
            <div class="modal-body">
                <a href="{{ route('lamps.create') }}" >
                    Лампа
                </a>

                <br><br>

                <a href="{{ route('led_tapes.create') }}" >
                    Led лента
                </a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_add_illumination_init_btn" style="display: none;" data-toggle="modal" data-target="#modalAddIllumination">&nbsp;</button>
