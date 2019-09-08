@extends('layouts._layout')

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
                                        <div class="col col-md-{{ count($data['termostat_'.$termostat->id]['values']) > 20 ? 11 : 6}}">
                                            <canvas id="chart{{$termostat->id}}"></canvas>
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
                @else
                    <p>Нет данных</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
@endsection

@section('scripts')
    <script src="{{ asset('ela/js/lib/chart-js/Chart.bundle.js') }}"></script>
    <script>
        function createChart(id, code, values, labels) {
            let ctx = document.getElementById("chart"+id);
            //ctx.height = 300;
            let myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "", //"Термостат "+code,
                            borderColor: "rgba(0, 123, 255, 0.9)",
                            borderWidth: "1",
                            backgroundColor: "rgba(0, 123, 255, 0.5)",
                            pointHighlightStroke: "rgba(26,179,148,1)",
                            data: values,
                            yAxisID: 'yid'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        yAxes: [{
                            id: 'yid',
                            type: 'linear'
                        }]
                    },
                    legend: {
                        display: false
                    }
                }
            } );

            myChart.update();
        }

        $(document).ready(function(){
            let id = 0;
            let code = '';
            let values = [];
            let labels = [];

            @foreach($data['rooms'] as $room)
                @foreach($room->termostats as $termostat)
                    @if(count($data['termostat_'.$termostat->id]['values']))
                        id = '{{ $termostat->id }}';
                        code = '{{ $termostat->id_termometr }}';
                        values = {{ json_encode($data['termostat_'.$termostat->id]['values']) }};
                        labels =  {!! json_encode($data['termostat_'.$termostat->id]['labels']) !!};
                        createChart(id, code, values, labels);
                    @endif
                @endforeach
            @endforeach
        });
    </script>
@endsection
