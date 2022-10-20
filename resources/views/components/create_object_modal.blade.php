<div id="create_object_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="create_object_modal_title">Создание объекта</h5>
            </div>
            <div class="modal-body text-left" id="create_object_modal_body">
                <div class="alert alert-danger alert-dismissible fade show" id="create_object_modal_error_div" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <span id="create_object_modal_error"></span>
                </div>
                {{ Form::bs_radio('object_type', 'Тип объекта*:', $object_types, null, ['required' => true]) }}
                {{ Form::bs_text('object_name', 'Название*:', null, ['required' => true]) }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="create_object_modal_btn">Создать объект</button>
                <button type="button" class="btn btn-default" id="create_object_cancel_btn" data-dismiss="modal">Отмена</button>
            </div>
        </div>
    </div>
</div>

<button type="button" id="create_object_modal_init_btn" style="display: none;" data-toggle="modal" data-target="#create_object_modal">&nbsp;</button>
