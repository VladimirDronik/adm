<div id="paramsModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Введите параметры метода</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="params_error_div" style="display: none;">
                    <span id="params_error_text"></span>
                </div>
                <input type="hidden" name="paramsMethodId" autocomplete="off" id="paramsMethodId" value="">
                <input type="hidden" name="paramsMethodName" autocomplete="off" id="paramsMethodName" value="">
                <span class="" id="paramsLabel"></span>
                <input type="text" class="form-control input-default" id="param" placeholder="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" id="paramsApplyBtn">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<button type="button" id="params_modal_init_btn" style="display: none;"
        data-toggle="modal" data-target="#paramsModal">&nbsp;</button>