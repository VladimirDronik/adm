<div id="param_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="top: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="param_modal_title">Добавление параметра</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="m_error_div" style="display: none;">
                    <span id="m_error_text"></span>
                </div>
                <input type="hidden" id="param_id" name="param_id" value="">
                @if($sensorSettings->where('name', 'type')->first()?->value == 'custom')
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_name">
                            <strong>Название*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_name" id="param_name" required type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_param">
                            <strong>Символьный код*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_param" id="param_param" required type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_get_param">
                            <strong>Get Param:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_get_param" id="param_get_param" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_value">
                            <strong>Значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_value" id="param_value" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_units">
                            <strong>Ед. измерения:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_units" id="param_units" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_accuracy">
                            <strong>Точность*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_accuracy" id="param_accuracy" required type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_graph">
                            <strong>График:</strong>
                        </label>
                        <div class="col-md-9">
                            <input type="checkbox" name="param_graph" id="param_graph" value="1" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_min_range">
                            <strong>Минимальное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_min_range" id="param_min_range" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_max_range">
                            <strong>Максимальное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_max_range" id="param_max_range" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_min_alarm">
                            <strong>Мин. аварийное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_min_alarm" id="param_min_alarm" type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_max_alarm">
                            <strong>Макс. аварийное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_max_alarm" id="param_max_alarm" type="text" class="form-control" value="">
                        </div>
                    </div>
                @else
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_name">
                            <strong>Название*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_name" id="param_name" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_param">
                            <strong>Символьный код*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_param" id="param_param" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_get_param">
                            <strong>Get Param:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_get_param" id="param_get_param" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_value">
                            <strong>Значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_value" id="param_value" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_units">
                            <strong>Ед. измерения:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_units" id="param_units" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_accuracy">
                            <strong>Точность*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_accuracy" id="param_accuracy" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_graph">
                            <strong>График:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_graph_text" id="param_graph_text" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_min_range">
                            <strong>Минимальное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_min_range" id="param_min_range" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_max_range">
                            <strong>Максимальное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_max_range" id="param_max_range" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_min_alarm">
                            <strong>Мин. аварийное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_min_alarm" id="param_min_alarm" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_max_alarm">
                            <strong>Макс. аварийное значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_max_alarm" id="param_max_alarm" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                @endif
                <div class="form-group row" id="div_param_timestamp">
                    <label class="control-label text-right col-md-3 label-fix" for="param_timestamp">
                        <strong>Timestamp:</strong>
                    </label>
                    <div class="col-md-9">
                        <input autocomplete="off" name="param_timestamp" id="param_timestamp" readonly type="text" class="form-control" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if($sensorSettings->where('name', 'type')->first()?->value == 'custom')
                    <button type="button" class="btn btn-primary" id="apply_btn">Добавить параметр</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_btn">Отмена</button>
                @else
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_btn">Закрыть</button>
                @endif
            </div>
        </div>
    </div>
</div>