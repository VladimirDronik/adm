<!-- модальное окно считывания кода с пульта -->
<div class="modal" id="modalReadCode">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReadCodeTitle">Считать код</h5>
            </div>
            <div class="modal-body">
                Направьте пульт на считыватель и нажмите кнопку
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_read_code_close" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_read_code_init_btn" style="display: none;" data-toggle="modal" data-target="#modalReadCode">&nbsp;</button>
<!-- модальное окно полученного кода с пульта -->
<div class="modal" id="modalReceivedCode">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReceivedCodeTitle">Считан код</h5>
            </div>
            <div class="modal-body">
                Cчитан код: <input type="text" class="form-control input-default col-sm-12" id="receivedCode" size="15" value="">
                <br>
                <button type="button" id="modal_read_code_repeat" class="btn btn-default" data-dismiss="modal">Считать заново</button>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-default" data-dismiss="modal">Сохранить</button>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_received_code_close" class="btn btn-default" data-dismiss="modal">Отменить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="modal_received_code_init_btn" style="display: none;" data-toggle="modal" data-target="#modalReceivedCode">&nbsp;</button>
