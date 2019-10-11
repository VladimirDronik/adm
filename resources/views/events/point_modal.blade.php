<div id="point_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="point_modal_title">Добавление периода</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="m_error_div" style="display: none;">
                    <span id="m_error_text"></span>
                </div>
                <input type="hidden" id="m_id" name="m_id" value="">
                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Тип*:</strong>
                    </label>
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label style="cursor: pointer;">
                                <input type="radio" checked name="m_type" value="c" autocomplete="off"> Ежеминутно
                            </label>
                        </div>
                        <div class="checkbox">
                            <label style="cursor: pointer;">
                                <input type="radio" name="m_type" value="w" autocomplete="off"> Ежедневно
                            </label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="checkbox">
                            <label style="cursor: pointer;">
                                <input type="radio" name="m_type" value="m" autocomplete="off"> Ежемесячно
                            </label>
                        </div>
                        <div class="checkbox">
                            <label style="cursor: pointer;">
                                <input type="radio" name="m_type" value="y" autocomplete="off"> Ежегодно
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group row" id="m_div_c" style="min-height: 100px;">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Период*:</strong>
                    </label>
                    <div class="col-md-9">
                        <select name="m_cron_period" class="form-control">
                            @foreach($cron_periods as $cron_period)
                                <option value="{{ $cron_period }}">{{ $cron_period }} мин</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="m_div_clock" style="display: none;">
                    <label class="control-label text-right col-md-3 label-fix" for="m_time">
                        <strong>Время*:</strong>
                    </label>
                    <div class="col-md-9">
                        <div class="input-group clockpicker" data-autoclose="true" id="clockpicker">
                            <input autocomplete="off" name="m_time" id="m_time" required type="text" class="form-control" value="">
                        </div>
                    </div>
                </div>
                <div class="form-group row" id="m_div_w" style="display: none;">
                    <label class="control-label text-right col-md-3 label-fix" for="m_time">
                        <strong>Дни недели*:</strong>
                    </label>
                    <div class="col-md-9" style="padding-top: 10px;">
                        @foreach(['Пн','Вт','Ср','Чт','Пт','Сб','Вс'] as $key => $day)
                            <div class="checkbox m-r-10" style="display: inline-block;">
                                <label style="cursor: pointer;">
                                    <input type="checkbox" name="m_days" value="{{ $key }}" autocomplete="off"> {{ $day }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row" id="m_div_m" style="display: none;">
                    <label class="control-label text-right col-md-3 label-fix" for="m_time">
                        <strong>Даты месяца*:</strong>
                    </label>
                    <div class="col-md-9">
                        @for($i = 1; $i <= 31; $i++)
                            <div class="checkbox m-r-10" style="display: inline-block;">
                                <label style="cursor: pointer;">
                                    <input type="checkbox" name="m_dates" value="{{ $i }}" autocomplete="off"> {{ $i }}
                                </label>
                            </div>
                        @endfor
                    </div>
                </div>
                <div class="form-group row" id="m_div_y" style="display: none;">
                    <label class="control-label text-right col-md-3 label-fix" for="m_time">
                        <strong>Даты*:</strong>
                    </label>
                    <div class="col-md-9">
                        <div id="m_div_year_dates" class="p-t-4">
                        </div>
                        <hr>
                        <form class="form-inline my-2 my-lg-0">
                            <input class="form-control mr-sm-2" id="m_year_date" type="text" autocomplete="off" name="m_year_dates" value="">
                            <button class="form-control btn btn-default m-l-4 my-2 my-sm-0" id="add_year_date_btn" type="button">Добавить</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="apply_btn">Добавить период</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_btn">Отмена</button>
            </div>
        </div>
    </div>
</div>