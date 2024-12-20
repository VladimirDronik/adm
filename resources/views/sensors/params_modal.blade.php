<div id="param_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="top: 65%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="param_modal_title">Добавление параметра</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="m_error_div" style="display: none;">
                    <span id="m_error_text"></span>
                </div>
                <input type="hidden" id="param_id" name="param_id" value="">
                @if($sensorSettings->where('name', 'type')->first()?->value == 'custom' || $sensorSettings->where('name', 'connection')->first()?->value == '1wbus')
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_name">
                            <strong>Название*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_name" id="param_name" required type="text" class="form-control" value="">
                        </div>
                    </div>
                    @if($sensorSettings->where('name', 'connection')->first()?->value == '1wbus')
                        <div id="param_param_select_div">
                            {{ Form::bs_select('param_param', 'Измеряемый параметр*:', $params, old('param_param'), ['required' => true]) }}
                        </div>
                        <div class="form-group row" id="param_param_input_div">
                            <label class="control-label text-right col-md-3 label-fix" for="param_param">
                                <strong>Измеряемый параметр*:</strong>
                            </label>
                            <div class="col-md-9">
                                <input autocomplete="off" name="param_param_input" id="param_param_input" readonly type="text" class="form-control" value="">
                            </div>
                        </div>
                    @else
                        {{ Form::bs_select('param_param', 'Измеряемый параметр*:', $params, old('param_param'), ['required' => true]) }}
                    @endif
                    @switch($sensorSettings->where('name', 'source')->first()?->value)
                        @case('megad')
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="param_get_param">
                                    <strong>Get запрос:</strong>
                                </label>
                                <div class="col-md-9">
                                    <input autocomplete="off" name="param_get_param" id="param_get_param" @if($sensorSettings->where('name', 'type')->first()?->value != 'custom') readonly @endif type="text" class="form-control" value="">
                                </div>
                            </div>
                            @break
                        @case('modbus')
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="param_get_param">
                                    <strong>ID регистра:</strong>
                                </label>
                                <div class="col-md-9">
                                    <input autocomplete="off" name="param_get_param" id="param_get_param" type="text" class="form-control" value="">
                                </div>
                            </div>
                            @break
                        @case('mqtt')
                            <div class="form-group row">
                                <label class="control-label text-right col-md-3 label-fix" for="param_get_param">
                                    <strong>Топик:</strong>
                                </label>
                                <div class="col-md-9">
                                    <input autocomplete="off" name="param_get_param" id="param_get_param" type="text" class="form-control" value="">
                                </div>
                            </div>
                            @break
                    @endswitch
                    <div class="form-group row" id="param_value_div">
                        <label class="control-label text-right col-md-3 label-fix" for="param_value">
                            <strong>Значение:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_value" id="param_value" type="text" class="form-control" value="">
                        </div>
                    </div>
                    @if($sensorSettings->where('name', 'connection')->first()?->value == '1wbus')
                        <div class="form-group row">
                            <label class="control-label text-right col-md-3 label-fix" for="param_units">
                                <strong>Ед. измерения*:</strong>
                            </label>
                            <div class="col-md-9">
                                <input autocomplete="off" name="param_units_name" id="param_units_name" readonly type="text" class="form-control" value="°C">
                                <input autocomplete="off" name="param_units" id="param_units" hidden type="text" class="form-control" value="celsius">
                            </div>
                        </div>
                    @else
                        {{ Form::bs_select('param_units', 'Ед. измерения*:', $units, old('param_units', null), ['required' => true]) }}
                    @endif
                    {{ Form::bs_radio('param_accuracy', 'Точность (знаков после запятой)*:', [0 => '0', 1 => '1', 2 => '2'], null, ['required' => true]) }}
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
                            <input autocomplete="off" name="param_name" id="param_name" required type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_param">
                            <strong>Измеряемый параметр*:</strong>
                        </label>
                        <div class="col-md-9">
                            <input autocomplete="off" name="param_param" id="param_param" readonly type="text" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label text-right col-md-3 label-fix" for="param_get_param">
                            <strong>Get запрос:</strong>
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
                    {{ Form::bs_radio('param_accuracy', 'Точность (знаков после запятой)*:', [0 => '0', 1 => '1', 2 => '2'], null, ['required' => true]) }}
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
                @endif
                <div class="form-group row" id="div_param_timestamp">
                    <label class="control-label text-right col-md-3 label-fix" for="param_timestamp">
                        <strong>Время последнего значения:</strong>
                    </label>
                    <div class="col-md-9">
                        <input autocomplete="off" name="param_timestamp" id="param_timestamp" readonly type="text" class="form-control" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="apply_btn">Добавить параметр</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_btn">Отмена</button>
            </div>
        </div>
    </div>
</div>