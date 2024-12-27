<div id="graph_modal" class="modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="col-md-8">
                    <h4 class="modal-title" id="graph_modal_title">График параметра</h4>
                </div>
                <div class="col-md-4">
                    <select class="form-control select_period" id="select_period" autocomplete="off">
                        <option value="7" selected>за последние 7 дней</option>
                        @foreach($periods as $key => $period)
                            <option value="{{ $key }}">{{ $period }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-body">
                <div class="col col-md-12">
                    <div id="chart" class="chartdiv" style="height: 450px;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="graph_close_btn">Закрыть</button>
            </div>
        </div>
    </div>
</div>