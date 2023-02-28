<div class="col-sm-12 pr-0 mt-4">
    {{ Form::bs_autoselect('operationMode', 'Режим работы:', $operationModes, null, false, false, ['required' => false], null, null, 3, true) }}
    {{ Form::bs_autoselect('fanMode', 'Вентилятор:', $fanModes, null, false, false, ['required' => false], null, null, 3, true) }}
    {{ Form::bs_autoselect('temp', 'Температура:', $temp, null, false, false, ['required' => false], null, null, 3, true) }}

    <div id='code_div' style="display: none">
        <div class="form-group row ">
            <label class="control-label text-right col-md-3 label-fix" for="code">
                <strong>Код:</strong>
            </label>
            <div class="col-md-9">
                <div id="code"></div>
                <br>
                <button type="button" class="btn btn-success m-b-10 m-l-5" id="readCodeBtn">Считать</button>
            </div>
        </div>
    </div>

</div>