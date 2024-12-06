<div id="address_param_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="address_param_modal_title">Добавление адреса</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="m_error_div" style="display: none;">
                    <span id="m_error_text"></span>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="param_name">
                        <strong>Адрес*:</strong>
                    </label>
                    <div class="col-md-9">
                        <input autocomplete="off" name="address_param_address" id="address_param_address" required type="text" class="form-control" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="address_apply_btn">Добавить адрес</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="address_cancel_btn">Отмена</button>
            </div>
        </div>
    </div>
</div>