

<div id="event_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" id="event_modal_content">
            <div class="modal-header">
                <h4 class="modal-title" id="event_modal_title">Добавление события</h4>
            </div>
            <div class="modal-body">

                <div class="alert alert-danger" id="alert_div" role="alert" style="display: none">
                    Не заполнены обязательные поля!
                </div>

                <div class="alert alert-danger alert-dismissible fade show" id="events_error_div" style="display: none;">
                    <span id="events_error_text"></span>
                </div>
                <input type="hidden" id="event_id" name="event_id" value="">
                <input type="hidden" id="event_mode" name="event_mode" value="">

                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="event_name">
                        <strong>Название*:</strong>
                    </label>
                    <div class="col-md-9">
                        <input class="form-control"  name="event_name" id="event_name" type="text" value="">
                    </div>
                </div>


                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="event_name">
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
                    <label class="control-label text-right col-md-3 label-fix" for="event_name">
                        <strong>Значение:</strong>
                    </label>
                        <div class="col-md-9">
                            <div class="d-flex flex-row justify-content-between">

                                <select name="m_property" id="m_property" autocomplete="off" class="form-control" style="width:auto;">
                                    <option value="">Не указано</option>
                                    @foreach($properties as $key => $property)
                                        @if($property[1])  Если свойство доступно для чтения
                                        <option value="{{ $key }}">{{ $property[0] }}</option>
                                        @endif
                                    @endforeach
                                </select>

                                <select name="m_comparison" id="m_comparison" autocomplete="off" class="form-control" style="width:auto;">
                                    <option value="="> = </option>
                                    <option value=">"> > </option>
                                    <option value="<"> < </option>
                                    <option value="!="> != </option>
                                </select>
                                <input class="form-control"  name="event_value" id="event_value" type="text" value="" size="6"
                                       style="height: 33px;">
                            </div>
                        </div>
                </div>


                <div class="form-group row">
                    <label class="control-label text-right col-md-3 label-fix" for="event_name">
                        <strong>Выполнить действия:</strong>
                    </label>


                    <div class="col-md-9">

                        <div id="actions_div" style="font-family: 'FontAwesome', Helvetica;">

                        </div>

                        <br>
                        <button type="button" class="btn btn-success addAction_btn"  style="height: 33px;">
                            Добавить действие</button>


                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="applyEvent_btn">Сохранить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="cancelEvent_btn">Отмена</button>
            </div>
        </div>
    </div>
</div>


