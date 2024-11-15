@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Датчики</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Датчики</li>
                <li class="breadcrumb-item active">Датчики</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'sensors'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('sensors.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик</a>
                        <a href="{{ route('sensors.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Датчики</h4></div>
            <div class="card-body">
                @if(count($sensorObjects))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Параметры</th>
                                    <th>Помещение</th>
                                    <th>Статус</th>
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sensorObjects as $sensorObject)
                                    <tr id="tr{{$sensorObject->id}}">
                                        <td>
                                            {{ $sensorObject->id }}
                                        </td>
                                        <td>
                                            <a href="{{ route('sensors.edit', [$sensorObject->id]) }}">{{ $sensorObject->name }}</a>
                                        </td>
                                        <td>
                                            @foreach($sensorObject->sensorsParams as $sensorsParam)
                                                {{ $sensorsParam->name }}: {{ $sensorsParam->value ? ($sensorsParam->value . ' ' . $sensorsParam->units) : '' }}
                                                <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ \App\Models\Room::find($sensorObject->sensors()->where('name', 'room')->first()?->value)?->name }}
                                        </td>
                                        <td>
                                            @if($sensorObject->status == 'ok')
                                                <span class="badge badge-success">Активно</span>
                                            @else
                                                <span class="badge badge-danger">Недоступно</span>
                                            @endif
                                        </td>
                                        <td align="center" class="text-center">
                                            <a href="{{ route('sensors.edit', [$sensorObject->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $sensorObject->id }}" data-name="{{ $sensorObject->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($sensorObjects) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Параметры</th>
                                        <th>Помещение</th>
                                        <th>Статус</th>
                                        <th style="width: 60px;"></th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $sensorObjects->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $sensorObjects->total() }}</p>
                @else
                    <p>Датчики не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.info_modal')
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить датчик № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: "{{ route('ajax.objects.sensor.delete') }}",
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении датчика');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
