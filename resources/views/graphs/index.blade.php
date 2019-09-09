@extends('layouts._layout')

@section('css')
    <style>
        .chartdiv {
            height: 500px !important;
        }
    </style>
@endsection

@section('breadcrumbs')
    @includeIf('components.breadcrumbs', ['title' => 'Графики'])
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('graphs.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                @if(count($data))
                    @foreach($data['rooms'] as $room)
                        <h3>Помещение «{{ $room->name }}»</h3>
                        @if(count($room->termostats))
                            @foreach($room->termostats as $termostat)
                                <h4>Термостат «{{ $termostat->id_termometr }}»</h4>
                                @if(count($data['termostat_'.$termostat->id]['values']))
                                    <div class="row">
                                        <div class="col col-md-12">
                                            <div id="chart{{$termostat->id}}" class="chartdiv"></div>
                                        </div>
                                    </div>
                                @else
                                    <p>Нет данных</p>
                                @endif
                            @endforeach
                        @else
                            <p>Нет термостатов</p>
                        @endif
                        <hr>
                    @endforeach
                    @if(count($data['other_termostats']))
                        <h3>Остальные термостаты</h3>
                        @foreach ($data['other_termostats'] as $termostat)
                            <h4>Термостат «{{ $termostat->id_termometr }}»</h4>
                            @if(count($data['termostat_'.$termostat->id]['values']))
                                <div class="row">
                                    <div class="col col-md-12">
                                        <div id="chart{{$termostat->id}}" class="chartdiv"></div>
                                    </div>
                                </div>
                            @else
                                <p>Нет данных</p>
                            @endif
                        @endforeach
                    @endif
                @else
                    <p>Нет данных</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/amcharts4/core.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/charts.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/themes/animated.js') }}"></script>
    <script src="{{ asset('ela/js/lib/amcharts4/lang/ru_RU.js') }}"></script>
    <script>
        $(document).ready(function(){

            am4core.ready(function() {
                // Themes begin
                am4core.useTheme(am4themes_animated);
                // Themes end

                let id = 0;
                let dates = [];
                let values = [];

                @foreach($data['rooms'] as $room)
                    @foreach($room->termostats as $termostat)
                        @if(count($data['termostat_'.$termostat->id]['values']))
                            id = '{{ $termostat->id }}';
                            values = {{ json_encode($data['termostat_'.$termostat->id]['values']) }};
                            dates = {!! json_encode($data['termostat_'.$termostat->id]['labels']) !!};
                            createAmChart(id, dates, values);
                         @endif
                     @endforeach
                @endforeach

                @foreach ($data['other_termostats'] as $termostat)
                    @if(count($data['termostat_'.$termostat->id]['values']))
                        id = '{{ $termostat->id }}';
                        values = {{ json_encode($data['termostat_'.$termostat->id]['values']) }};
                        dates = {!! json_encode($data['termostat_'.$termostat->id]['labels']) !!};
                        createAmChart(id, dates, values);
                    @endif
                @endforeach

                function createAmChart(id, dates, values) {
                    // Create chart
                    var chart = am4core.create("chart"+id, am4charts.XYChart);
                    chart.paddingRight = 20;
                    chart.language.locale = am4lang_ru_RU;
                    chart.data = getChartData(dates, values);

                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    dateAxis.baseInterval = {
                        "timeUnit": "minute",
                        "count": 1
                    };
                    dateAxis.tooltipDateFormat = "dd.MM.yyyy HH:mm";

                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.tooltip.disabled = true;
                    valueAxis.title.text = "Температура";

                    var series = chart.series.push(new am4charts.LineSeries());
                    series.dataFields.dateX = "date";
                    series.dataFields.valueY = "temp";
                    series.tooltipText = "T: [bold]{valueY}[/]";
                    series.fillOpacity = 0.3;

                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.lineY.opacity = 0;
                    chart.scrollbarX = new am4charts.XYChartScrollbar();
                    chart.scrollbarX.series.push(series);

                    chart.events.on("datavalidated", function () {
                        dateAxis.zoom({start:0.85, end:1});
                    });
                }

                function getChartData(dates, values) {
                    var chartData = [];
                    for (var i = 0; i < dates.length; i++) {
                        chartData.push({
                            date: new Date(dates[i]),
                            temp: values[i]
                        });
                    }
                    return chartData;
                }

            }); // end am4core.ready()
        });
    </script>
@endsection
