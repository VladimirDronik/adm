

<div id="event_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="method_modal_title">Добавление метода</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger alert-dismissible fade show" id="methods_error_div" style="display: none;">
                    <span id="methods_error_text"></span>
                </div>
                <input type="hidden" id="m_id" name="m_id" value="">


                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Название*:</strong>
                    </label>
                    <div class="col-md-9">
                        <input class="form-control" required name="m_name" type="text" value="">
                    </div>
                </div>


                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Если при событии:</strong>
                    </label>
                    <div class="col-md-9">
                        <select name="m_event" id="m_event" autocomplete="off" class="form-control" >
                            <option value="">Не указано</option>
                            @foreach($availableEvents as $key => $name)
                                <option value="{{ $key }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Значение:</strong>
                    </label>
                        <div class="col-md-9">
                            <div class="d-flex flex-row justify-content-between">

                                <select name="m_property" id="m_property" autocomplete="off" class="form-control" style="width:auto;">
                                    <option value="">Не указано</option>
                                    @foreach($properties as $key => $property)
                                        @if($property[1])
                                        <option value="{{ $key }}">{{ $property[0] }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <select name="m_comparison" id="m_comparison" autocomplete="off" class="form-control" style="width:auto;">
                                    <option value=">"> > </option>
                                    <option value="<"> < </option>
                                    <option value="="> = </option>
                                    <option value="!="> != </option>
                                </select>
                                <input class="form-control" required name="m_value" id="m_value" type="text" value="" size="6"
                                       style="height: 33px;">
                            </div>
                        </div>
                </div>


                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Выполнить действия:</strong>
                    </label>


                    <div class="col-md-9">

                        <div id="actions_div"  style="font-family: 'FontAwesome', Helvetica;"></div>

                        <div class="d-flex flex-row justify-content-between">
                            <select name="m_event" autocomplete="off" class="form-control" style="width:auto; font-family: 'FontAwesome', Helvetica;" >
                                <option value="">Не указано</option>
                                <option value="script">&#xf085;&nbsp;Вызов скрипта</option>
                                <option value="method">&#xf0c1;&nbsp;Вызов метода</option>
                                <option value="notification">&#xf1d8;&nbsp;Отправка уведомления</option>
                                <option value="sound">&#xf0f3;&nbsp;Проигрывание звука</option>
                                <option value="property">&#xf1e8;&nbsp;Изменение свойства</option>
                                <option value="view">&#xf247;&nbsp;Управление отображением</option>
                                <option value="log">&#xf044;&nbsp;Запись в лог</option>
                            </select>

                            &nbsp; <button type="button" class="btn btn-success" id="add_btn" style="height: 33px;">
                                <b>+</b></button>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix">&nbsp;
                    </label>
                    <div class="col-md-9">
                        <div class="btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-success active" id="easy_button">
                                <input type="radio" name="actions" checked autocomplete="off" value="easy"> Простое действие
                            </label>
                            <label class="btn btn-success" id="script_button">
                                <input type="radio" name="actions"  autocomplete="off" value="script"> Скрипт
                            </label>
                            <label class="btn btn-success" id="none_button">
                                <input type="radio" name="actions"  autocomplete="off" value="none"> Отсутствует
                            </label>
                        </div>
                    </div>
                </div>


                <div class="form-group row" id="easy_div">
                    <label class="control-label text-right col-md-3 label-fix"></label>
                    <div class="col-md-9">
                        <button type="button" class="btn btn-success m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="dev_select_button" onclick="loadSubData('device');">Контроллер: <span id="easy_device">отсутствует</span></button>&nbsp;
                        <button type="button" class="btn btn-success m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="port_btn" onclick="loadSubData('port');"><span id="portordevice">Порт: </span><span id="easy_port">отсутствует</span></button>&nbsp;
                        <button type="button" class="btn btn-success m-b-10 btn-sm" data-toggle="modal" data-target="#methodsModal" id="action_btn" onclick="loadSubData('action');">Действие: <span id="easy_action">отсутствует</span></button>
                    </div>
                </div>
                <div class="form-group row" id="script_div" style="display: none;">
                    <label class="control-label text-right col-md-3 label-fix" for="m_script">
                        <strong>Скрипт*:</strong>
                    </label>
                    <div class="col-md-9">
                        <select name="m_script" autocomplete="off" class="form-control">
                            <option value="">Не указан</option>
                            @foreach($scripts as $key => $script)
                                <option value="{{ $key }}">{{ $script }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>





            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="apply_btn">Добавить метод</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancel_btn">Отмена</button>
            </div>
        </div>
    </div>
</div>

<div id="methodsModal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_action"></h4>
            </div>
            <div class="modal-body" id="method_data">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" >Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>
