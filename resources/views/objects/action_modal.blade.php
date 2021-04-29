
<div id="action_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="method_modal_title">Добавление действия</h4>
            </div>
            <div class="modal-body">

                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="m_name">
                        <strong>Выполнить действие:</strong>
                    </label>

                    <div class="col-md-9">

                        <div class="d-flex flex-row justify-content-between">
                            <select name="type_action" id="type_action" autocomplete="off" class="form-control" style="width:auto; font-family: 'FontAwesome', Helvetica;" >
                                <option value="">Не указано</option>
                                <option value="script">&#xf085;&nbsp;Вызов скрипта</option>
                                <option value="method">&#xf0c1;&nbsp;Вызов метода</option>
                                <option value="notification">&#xf1d8;&nbsp;Отправка уведомления</option>
                                <option value="sound">&#xf0f3;&nbsp;Проигрывание звука</option>
                                <option value="property">&#xf1e8;&nbsp;Изменение свойства</option>
                                <option value="view">&#xf247;&nbsp;Управление отображением</option>
                                <option value="log">&#xf044;&nbsp;Запись в лог</option>
                            </select>
                        </div>

                        <br>


                        <div id="script_action_div" style="display: none">
                            {{ Form::bs_autoselect('action_script', 'Скрипт', $scripts, old('action_script'),
                                   false, false, [], null, '') }}
                        </div>

                        <div id="object_action_div" style="display: none">

                            {{ Form::bs_autoselect('action_object', 'Объект', $objects, old('action_object'),
                                  false, false, [], null, '') }}
                        </div>

                        <div id="method_action_div" style="display: none">


                            {{ Form::bs_autoselect('action_method', 'Метод', [], old('action_method'),
                                   false, false, [], null, '') }}
                        </div>


                        <div id="notif_action_div" style="display: none">
                            Текст оповещения:
                            <textarea id="action_notif" name="action_notif" cols="60" rows="5"></textarea>
                        </div>


                        <div id="sound_action_div" style="display: none">
                            {{ Form::bs_autoselect('action_sound', 'Звук оповещения', $sounds, old('action_sound'),
                                   false, false, [], null, '') }}
                        </div>



                        <div id="property_action_div" style="display: none">

                            {{ Form::bs_autoselect('action_property', 'Свойство объекта', [], old('action_property'),
                                   false, false, [], null, '') }}
                        </div>


                        <div id="property_value_div" style="display: none">
                             {{ Form::bs_text('action_value', 'Значение для свойства:', null, [], old('action_value')) }}
                        </div>



                        <div id="view_action_div" style="display: none">
                            {{ Form::bs_autoselect('action_view', 'отображение:', $views, old('action_view'),
                                       false, false, [], null, '') }}

                            {{ Form::bs_radio('view_status', 'Состояние отображения:', ['on' => 'активно', 'off' => 'не активно'], old('view_status', -1)) }}
                        </div>


                        <div id="log_action_div" style="display: none">
                            Текст лог сообщения:
                            <textarea id="action_log" name="action_log" cols="60" rows="5"></textarea>
                        </div>

                        <input id="type_action_selected" name="type_action_selected" style="display: none">
                        <input id="type_action_selected" name="id_event" style="display: none">


                        <button type="button" class="btn btn-success createAction_btn"  data-dismiss="modal" id="addAction_btn" style="height: 33px;">
                            Сохранить</button>

                    </div>



                </div>
            </div>
        </div>
    </div>
</div>





