<!-- модальное окно настроек контроллера -->
<div id="settings_modal" class="modal">
    <div class="modal-dialog" style="max-width: 700px;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 label-fix" for="modalDevType">
                        <strong>Тип:</strong>
                    </label>
                    <div class="col-md-9" style="display: flex; align-items: center;">
                        {{ optional($device->devtype)->name }}
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 label-fix" for="modalDevName">
                        <strong>Название:</strong>
                    </label>
                    <div class="col-md-9">
                        <input id="modalDevName" class="form-control" name="description" autocomplete="off" value="{{ $device->description}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 label-fix" for="modalDevIp">
                        <strong>ip&nbsp;адрес:</strong>
                    </label>
                    <div class="col-md-9">
                        <input id="modalDevIp" class="form-control" name="ip_address" autocomplete="off" value="{{ $device->ip_address }}" size="15">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="control-label text-right col-md-2 label-fix" for="modalDevPassword">
                        <strong>Пароль:</strong>
                    </label>
                    <div class="col-md-9">
                        <input id="modalDevPassword" class="form-control" name="password" autocomplete="off" value="{{ $device->password }}">
                    </div>
                </div>
                <hr>
                <h4>Модули расширения:</h4>
                <br>
                @if($device->extensionModules)
                    @foreach($device->extensionModules as $extensionModule)
                    <div class="form-group row">
                        <label class="control-label text-right col-md-1 label-fix">
                            <strong>Тип:</strong>
                        </label>
                        <div class="col-sm-2" style="display: flex; align-items: center;">
                            {{ $extensionModule->extensionModuleType->name }}
                        </div>
                        <label class="control-label text-right col-md-1 label-fix">
                            <strong>SDA:</strong>
                        </label>
                        <div class="col-sm-1" style="display: flex; align-items: center;">
                            {{ $extensionModule->sda_port }}
                        </div>
                        <label class="control-label text-right col-md-1 label-fix">
                            <strong>SCL:</strong>
                        </label>
                        <div class="col-sm-1" style="display: flex; align-items: center;">
                            {{ $extensionModule->scl_port }}
                        </div>
                        <div class="col-sm-3"><button id="deleteExtensionModule{{ $extensionModule->id }}" onclick="deleteExtensionModule('{{ $extensionModule->id }}')" class="deleteExtensionModule btn btn-outline-danger">Удалить модуль</button></div>
                    </div>
                    @endforeach
                @endif
                <div id="extensionModulesContainer"></div>
                <button class="btn btn-success m-b-10 m-l-5" id="addExtensionModuleBtn">Добавить модуль</button>
                <input type="hidden" id="id_device" value="{{ $device->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" id="updateDeviceBtn" class="btn btn-success m-b-10 m-l-5" data-dismiss="modal" onclick="updateDevice();">Сохранить</button>
                <button type="button" id="deleteDeviceBtn" class="btn btn-outline-danger m-b-10 m-l-5 pull-right" data-dismiss="modal">Удалить контроллер</button>
            </div>
        </div>
    </div>
</div>