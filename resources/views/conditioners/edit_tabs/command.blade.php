<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('operationMode', 'Режим работы:', $operationModes, null, false, false, ['required' => true], null, null, 3, true, true) }}
    {{ Form::bs_autoselect('fanMode', 'Вентилятор:', $fanModes, null, false, false, ['required' => true], null, null, 3, true, true) }}
    {{ Form::bs_autoselect('temp', 'Температура:', $temp, null, false, false, ['required' => true], null, 'Для кода выключения кондиционера выберите "off"', 3, true, true) }}

    <div id='code_div'>
        <div class="form-group row ">
            <label class="control-label text-right col-md-3 label-fix" for="code">
                <strong>Код:</strong>
            </label>
            <div class="col-md-9">
                <div class="row" id="code">
                    <div class="col-sm-12  pr-0 ">
                        <input class="form-control" id="dataCode" disabled autocomplete="off" name="code" type="text" value="">
                    </div>
                    <div class="col-sm-12 pt-2 pr-0 text-right">
                        <input type="checkbox" id="codeCheckbox" name="codeCheckbox" value="1"> Ручной режим
                    </div>
                    &nbsp&nbsp&nbsp<button type="button" class="btn btn-success m-b-10 m-l-5" id="readCodeBtn">Считать</button>
                </div>
            </div>
        </div>
    </div>
</div>