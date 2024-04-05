@extends('layouts._layout')

@section('breadcrumbs')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Датчики: датчик давления</h3></div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
                <li class="breadcrumb-item breadcrumb-item-no-link">Устройства</li>
                <li class="breadcrumb-item breadcrumb-item-no-link"><a href="{{ route('pressurestats.index') }}">Датчики</a></li>
                <li class="breadcrumb-item active">Датчики давления</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('detectors.tab_header', ['active' => 'pressurestats'])
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('pressurestats.create') }}" class="btn btn-success m-b-10 m-l-5">Добавить датчик давления</a>
                        <a href="{{ route('pressurestats.index') }}" class="btn btn-success m-b-10 m-l-5">Обновить</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><h4>Датчики давления</h4></div>
            <div class="card-body">
                @if(count($pressurestats))
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Название</th>
                                    <th>Текущее давл.</th>
                                    <th>Оптим. давл.</th>
                                    <th>Гистерезис</th>
                                    <th>Режим</th>
                                    <th>Тип</th>
                                    @can('devices.show-object')
                                        <th>Объект влияния</th>
                                    @endcan
                                    <th style="width: 60px;"></th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pressurestats as $pressurestat)
                                    <tr id="tr{{$pressurestat->id}}">
                                        <td scope="row">{{ $pressurestat->id_object }}</td>
                                        <td>
                                            <a href="{{ route('pressurestats.edit', [$pressurestat->id]) }}">
                                                {{ $pressurestat->name }}
                                            </a>
                                        </td>
                                        <td>{{ $pressurestat->current }}</td>
                                        <td>{{ $pressurestat->optimal }}</td>
                                        <td>{{ $pressurestat->gisteresis }}</td>
                                        <td>{{ $pressurestat->rus_pressurestat }}</td>
                                        <td>{{ $pressurestat->rus_sensor_type }}</td>
                                        @can('devices.show-object')
                                            <td>
                                                {{ $pressurestat->influenceObject?->name }}
                                            </td>
                                        @endcan
                                        <td align="center" class="text-center">
                                            <a href="{{ route('pressurestats.edit', [$pressurestat->id]) }}" class="btn btn-info btn-sm btn-rounded">
                                                <i class="fa fa-cog fa-lg"></i>
                                            </a>
                                        </td>
                                        <td align="center" class="text-center">
                                            <button type="button" class="btn btn-danger btn-rounded btn-sm del_btn"
                                                    data-id="{{ $pressurestat->id }}" data-name="{{ $pressurestat->name }}">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            @if(count($pressurestats) > 10)
                                <tfoot>
                                    <tr>
                                        <th style="width: 60px;">ID</th>
                                        <th>Название</th>
                                        <th>Текущая давл.</th>
                                        <th>Оптим. давл.</th>
                                        <th>Гистерезис</th>
                                        <th>Режим</th>
                                        <th>Тип</th>
                                        @can('devices.show-object')
                                            <th>Объект влияния</th>
                                        @endcan
                                        <th style="width: 60px;"></th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>
                    {{ $pressurestats->appends(request()->input())->links() }}
                    <p class="text-right">Найдено: {{ $pressurestats->total() }}</p>
                @else
                    <p>Датчики давления не найдены</p>
                @endif
            </div>
        </div>
    </div>
    @include('components.del_modal')
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            let del_id;

            $('.del_btn').click(function() {
                del_id = $(this).data('id');
                $('#del_modal_body').text('Удалить датчик давления № '+del_id+' «'+$(this).data('name')+'»?');
                $('#del_init_btn').click();
            });

            $('#del_modal_btn').click(function(){
                if (del_id) {
                    $.ajax({
                        url: '{{ route('ajax.pressurestats.delete') }}',
                        data: { '_token': _token, 'id': del_id },
                        success: function (data) {
                            if (data.result) {
                                $('#tr'+del_id).hide();
                            } else {
                                showErrorModal('Ошибка при удалении датчика давления');
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
